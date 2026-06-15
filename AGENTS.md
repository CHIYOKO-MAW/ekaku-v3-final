# e-Kaku V3 — AGENTS.md

## TL;DR

AK-1 Kartu Kuning (job seeker card) management for Disnaker Pandeglang. Laravel 12, PHP 8.3, Blade + Alpine.js v3, Tailwind v3, Pest v4. SQLite default for dev/test. Three roles: `user`, `admin`, `atasan`. Main flow: User registers → submits biodata → admin verifies → system generates Kartu Kuning with QR code.

## Entity Lifecycle

```
User Registration
    ↓
Profile Created (role: user)
    ↓
Biodata Submission (status: pending)
    ↓
Admin Verification (approve/reject)
    ├─→ REJECTED: can resubmit
    └─→ VERIFIED: KartuKuning + QR token generated (HMAC-SHA256, 2-year expiry)
         ↓
    User can download PDF & share QR for verification
```

**Key States**: `Biodata.status_verifikasi`: `pending` → `verified` | `rejected`.

## Quick Reference

| What | Command |
|---|---|
| Dev server (all) | `composer run dev` |
| Quick server only | `php.bat artisan serve` |
| Queue worker | `php.bat artisan queue:work` |
| Scheduler | `php.bat artisan schedule:work` |
| Run all tests | `composer run test` (runs `config:clear` first) |
| Single test | `php.bat artisan test --compact --filter={Name}` |
| Format code | `vendor/bin/pint --dirty` |
| Clear Blade cache | `php.bat artisan view:clear` |
| Seed demo | `php.bat artisan db:seed --class=DemoDataSeeder` |

**Always use `php.bat` not `php`** (PHP is not in PATH; wrapper provided).

> `php.bat artisan serve` spawns a child `php -S` that does **not** inherit `-d` flags. In production, `start-container.sh` passes them directly: `-d upload_max_filesize=10M -d post_max_size=12M -d max_execution_time=120 -d max_input_time=120 -d memory_limit=256M -d zlib.output_compression=On -d opcache.enable=1 ...`.

## Role-Based Access Matrix

| Action | User | Admin | Atasan | Unauthenticated |
|--------|------|-------|--------|----------------|
| Register/Login | ✅ | ✅ | ✅ | ✅ |
| View own profile & biodata | ✅ | ✅ | — | — |
| Submit/edit biodata | ✅ | ✅ | — | — |
| Upload files (KTP, Ijazah, etc) | ✅ | ✅ | — | — |
| View own Kartu Kuning & QR | ✅ (verified) | — | — | — |
| Download Kartu PDF | ✅ (verified) | ✅ | — | — |
| List all users & biodata | — | ✅ | ✅ (read-only) | — |
| Verify/reject biodata | — | ✅ | — | — |
| Manage users | — | ✅ | — | — |
| Generate admin reports (CSV/PDF) | — | ✅ | — | — |
| View QR verification page | — | — | — | ✅ (throttled 30/min) |

## Key Business Workflows

### 1. Registration → Biodata → Uploads
- Register (email, password); profile created
- Multi-step biodata form (NIK, birth, education, skills, experience)
- Uploads: **ktp**, **ijazah**, **foto** (required); kk, sertifikat (optional)
- Files stored in `storage/app/public/uploads/`; validated `mimes:jpg,jpeg,png,pdf|max:5120`
- `UploadController@store` uses manual `Validator::make()` (not `$request->validate()`) to redirect to `profile.edit#berkas` with `withErrors()`

### 2. Admin Verification
- `VerifikasiController@update` sets `status_verifikasi = verified|rejected`
- On **verified**: auto-creates `KartuKuning` record (`AK1-{Ymd}-{user_id padded 4}`)
- Fires `BiodataStatusChanged` event → `SendBiodataStatusNotification` listener (Laravel Notification + in-app `NotificationE` record)

### 3. Kartu Kuning & QR
- Lazy-generated on preview/download via `ensureKartuKuningReady()`
- nomor: `AK1-{Ymd}-{user_id padded 4}` (e.g., `AK1-20260615-0001`)
- QR token: `hash_hmac('sha256', {user_id}:{nomor_ak1}:{timestamp}, APP_KEY)`
- Public verify URL: `/verify/{nomor_ak1}?token={token}` (throttled 30/min, no login)
- Foto profil dikirim sebagai base64 data URI di halaman verifikasi publik (hindari route `profile.photo` yg butuh auth)
- PDF via `KartuKuningController@generatePdf` (DOMPDF)

### 4. Status Pekerjaan & Smart Reminders
- User can update job status via `StatusPekerjaanController@store` (does NOT fire events)
- Daily job `SendSmartReminders` at 07:00: reminds users with stale status (6+ months) and kartu expiring in 30 days
- Uses Laravel Notifications (`StatusPekerjaanReminder`, `KartuKuningExpiryReminder`) + in-app `NotificationE` records

## Database Schema

| Table | Key Fields | Notes |
|-------|-----------|-------|
| `users` | id, email, password, role, profile_photo_path | `SoftDeletes`, role: `user\|admin\|atasan` |
| `biodata` | user_id, nik, tempat_lahir, alamat, pendidikan, status_verifikasi | `SoftDeletes`, 1:1 with User |
| `kartu_kuning` | user_id, nomor_ak1, qr_token, expired_at, scan_count, last_scanned_at | `SoftDeletes`, 1:1 with User |
| `uploads` | user_id, file_path, jenis_dokumen | jenis: `ktp\|ijazah\|foto\|kk\|sertifikat` |
| `notifications` (custom) | user_id, judul, pesan, tipe, status_baca | Custom `NotificationE` model, NOT Laravel's |
| `status_pekerjaan` | user_id, status_pekerjaan, nama_perusahaan, tanggal_update | 1:1 with User |
| `personal_access_tokens` | — | Laravel Sanctum |

## Models & SoftDeletes

`User`, `Biodata`, `KartuKuning` all use `SoftDeletes`. Queries exclude soft-deleted records by default. `StatusPekerjaan`, `Upload`, `NotificationE` do not use `SoftDeletes`.

Model methods: `User::hasCompletedProfile()` (checks biodata fields), `User::hasRequiredDocuments()` (ktp+ijazah+foto uploaded), `User::generateQrCode()` (HMAC token), `Biodata::isComplete()`.

## Queue & Jobs

**Connection**: `database` (no Redis). Jobs stored in `jobs` table.

| Job | Trigger | Behavior |
|-----|---------|----------|
| `SendSmartReminders` | Daily 07:00 via `schedule:work` | Stale status (6mo) & kartu expiring in 30 days |
| `SendBiodataStatusNotification` (listener) | `BiodataStatusChanged` event | Sends Laravel Notification + in-app `NotificationE` |

Other events: `StatusPekerjaanReminderEvent` (defined but not actively dispatched from controllers).

Other artisan commands: `CleanupOrphanUploads` in `app/Console/Commands/`.

## Security Highlights

- QR HMAC-SHA256 token (cannot forge without `APP_KEY`), public verify endpoint throttled 30/min
- Tokens expire 2 years; expired tokens show "Kartu Expired"
- Upload files served via `UploadController@preview` with auth (admin/owner only)
- CSP: `'unsafe-eval'` permanent (Alpine.js v3), Font Awesome CDN whitelisted
- `SecurityHeaders.php`: X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy, HSTS

## Performance Optimization

### Packages
- `spatie/laravel-responsecache` (v7.7.2) — HTTP response caching. Landing page cached 1h, auth routes excluded via `DoNotCacheResponse`. Uses `config/responsecache.php`.
- `livewire/blaze` (v1.0.12) — Blade pre-compilation. Configured in `AppServiceProvider::boot()`: `components` (compile), `layouts` (compile + fold).
- `Vite::prefetch()` — Built-in asset prefetching (3 concurrent), enabled in production via `AppServiceProvider::boot()`.

### Server Config (`start-container.sh`)
- OPcache: `opcache.enable=1, memory_consumption=128, max_accelerated_files=10000, validate_timestamps=0, save_comments=0`
- Compression: `zlib.output_compression=On` (falls back if `CompressionMiddleware` can't gzencode)
- Caches warmed at boot: `config:cache`, `route:cache`, `view:cache`, `event:cache`

### Cache Strategy (Routes)
| Route | Cache | Middleware |
|-------|-------|-----------|
| `GET /`, `/landing` | 1 hour | `CacheResponse::using(3600)` |
| `GET /verify/*` | No cache | Throttle only (30/min) |
| `GET /login`, `/register` | No cache | `DoNotCacheResponse` |
| All auth routes | No cache | `DoNotCacheResponse` in group |

### SecurityHeaders
Vite build assets (`/build/*`, `/favicon`, `/images/`) served with `Cache-Control: public, max-age=31536000, immutable`. All other routes get `no-cache, private`.

## Testing

Sidebar (`<div class="space-y-6">`) must be a **grid sibling** of `lg:col-span-2` inside `lg:grid-cols-3` — NOT nested. If sidebar moves below content, the column div wasn't closed.

Tab switching uses `x-init` with `setTimeout(() => { activeTab = ... }, 50)` reading `window.location.hash`. Inline ternary in `x-data` does NOT work.

Biodata container uses `x-show="activeTab !== 'akun'"` so both biodata AND berkas tabs show it; the inner content hides via `x-show="activeTab === 'biodata'"`.

## Important Files

- `routes/web.php:44` — auth routes group; `routes/auth.php` — Breeze auth
- `resources/views/user/profile.blade.php` — single-page tab layout (Alpine.js)
- `app/Http/Controllers/VerifikasiController.php:32` — verify/reject logic + KartuKuning creation
- `app/Http/Controllers/KartuKuningController.php:213` — `ensureKartuKuningReady()`
- `app/Http/Controllers/UploadController.php:32` — manual Validator for hash redirect
- `app/Models/User.php:92` — `generateQrCode()` (HMAC signing)
- `app/Http/Middleware/SecurityHeaders.php` — CSP & security headers
- `app/Http/Middleware/CompressionMiddleware.php` — gzip/brotli compression
- `start-container.sh` — production startup script with OPcache, caches, compression
- `config/responsecache.php` — response cache config (spatie/laravel-responsecache)
- `database/seeders/DemoDataSeeder.php` — 300+ users demo data
- `tests/Feature/Ak1WorkflowTest.php` — key integration test
- `database/data/pandeglang_wilayah.json` — wilayah data for reports

## Dev Environment Setup

```bash
composer run setup  # install, key:generate, migrate, npm install, build
```

Or step-by-step: `composer install` → `php.bat artisan key:generate` → `php.bat artisan migrate` → `npm install` → `npm run build`.

Pre-seeded accounts (password: `password`): `user@ekaku.test`, `admin@ekaku.test`, `atasan@ekaku.test`. Set `SEED_DEMO_DATA=true` in `.env` to auto-seed on migrate.

## MCP Tools

- `laravel-boost` (`php.bat artisan boost:mcp`) — tinker, routes, config, schema, browser logs
- `laravel-telescope` (`telescope-mcp:server`, requires `APP_ENV=local`) — debug queries, requests, exceptions
- `lighthouse` — performance & accessibility audits
- `filesystem` — full filesystem read/write to project directory

## LSP False Positives

Parser errors in `.blade.php` from CSS/SCSS inside `<style>` blocks are pre-existing and harmless.

## Deployment (Railway)

`nixpacks.toml` (Nixpacks, not Docker). `Procfile`: web (start-container.sh), worker (queue:work), cron (schedule:work). Session/cache/queue all `database` driver. Railway Volume `storage` (500MB) mounted at `/app/storage/app/public`. See `DEPLOYMENT.md` for details.

Production `php -S` flags: `upload_max_filesize=10M, post_max_size=12M, max_execution_time=120, max_input_time=120, memory_limit=256M, zlib.output_compression=On, opcache.enable=1, opcache.memory_consumption=128, opcache.max_accelerated_files=10000, opcache.validate_timestamps=0, save_comments=0`.
