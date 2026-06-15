# e-Kaku V3 — AGENTS.md

## TL;DR

AK-1 Kartu Kuning (job seeker card) management for Disnaker Pandeglang. Laravel 12, PHP 8.3, Blade + Alpine.js v3, Tailwind v3, Pest. SQLite default for dev/test. Three roles: `user`, `admin`, `atasan`. Main flow: User registers → submits biodata → admin verifies → system generates Kartu Kuning with QR code.

## Entity Lifecycle

```
User Registration
    ↓
Profile Created (role: user)
    ↓
Biodata Submission (status: pending)
    ↓
Admin Verification (approve/reject)
    ├─→ REJECTED: Biodata marked rejected, user can resubmit
    └─→ VERIFIED: Status = verified, triggers KartuKuning generation
         ↓
    KartuKuning Generated (auto-generated with nomor AK-1, issue date, expiry 2 years)
         ↓
    QR Token Created (HMAC-SHA256 signed, 2-year expiry)
         ↓
    User Can Download PDF & Share QR for verification
```

**Key States**: `Biodata.status_verifikasi`: `pending` → `verified` | `rejected`. `KartuKuning` created only when `status_verifikasi = verified`.

## Role-Based Access Matrix

| Action | User | Admin | Atasan (Supervisor) | Unauthenticated |
|--------|------|-------|---------------------|-----------------|
| Register/Login | ✅ | ✅ | ✅ | ✅ |
| View own profile & biodata | ✅ | ✅ | — | — |
| Submit/edit biodata | ✅ | ✅ | — | — |
| Upload files (KTP, Ijazah, etc) | ✅ | ✅ | — | — |
| View own Kartu Kuning & QR | ✅ (after verified) | — | — | — |
| Download Kartu PDF | ✅ (after verified) | ✅ | — | — |
| List all users & biodata | — | ✅ | ✅ (read-only) | — |
| Verify/reject biodata (approve workflow) | — | ✅ | — | — |
| Manage users (edit/delete) | — | ✅ | — | — |
| Generate admin reports (CSV/PDF) | — | ✅ | — | — |
| View QR verification page | — | — | — | ✅ (no login, throttled 30/min) |

## Key Business Workflows

### 1. User Registration & Biodata Submission
- User fills registration form (email, password verified)
- User navigates to Profile → Biodata tab
- Fills multi-step biodata form (NIK, birth date, education, skills, experience)
- Uploads required files: KTP, Ijazah, Foto (optional: KK, Sertifikat)
- System validates file types/sizes; stores in `storage/app/public/uploads`
- `Biodata.status_verifikasi` set to `pending`; admin gets notification

### 2. Admin Verification Workflow
- Admin logs in, sees list of pending biodata in verification dashboard
- Reviews biodata & uploaded files (preview in modal)
- Clicks "Terima" (approve) → `Biodata.status_verifikasi = verified`
  - Triggers event: `BiodataStatusChanged`
  - Listener: `SendBiodataStatusNotification` (in-app notification)
  - System auto-generates `KartuKuning` record with nomor (`AK1-{Ymd}-{user_id padded 4}`)
- Clicks "Tolak" (reject) → `Biodata.status_verifikasi = rejected`
  - User notified; can resubmit biodata

### 3. Kartu Kuning Generation & QR Signing
- After biodata verified: `VerifikasiController@update` creates KartuKuning record; auto-generated on preview/download via `ensureKartuKuningReady`
- Generates nomor: `AK1-{Ymd}-{user_id padded 4}` (e.g., `AK1-20260615-0001`)
- Creates 2-year expiry date (`now()->addYears(2)`)
- Generates QR token: HMAC-SHA256 signed string (`hash_hmac('sha256', ..., APP_KEY)`)
- QR points to public verification URL: `/verify/{nomor_ak1}?token={token}`
- User can download PDF via `KartuKuningController@generatePdf` (uses DOMPDF)

### 4. Status Update & Smart Reminders
- User can update job status: Belum Bekerja / Sudah Bekerja
- Stored via `StatusPekerjaanController@store` (does NOT fire events)
- Daily job (`SendSmartReminders`) at 07:00:
  - Sends status pekerjaan reminders: notifies users whose status hasn't been updated in 6+ months
  - Sends kartu expiry reminders: warns about kartu expiring in 30 days
  - Users see in-app notification on next login

## Database Schema Snapshot

| Table | Key Fields | Purpose |
|-------|-----------|---------|
| `users` | id, email, password, role (user\|admin\|atasan), email_verified_at | Authentication & authorization |
| `biodata` | id, user_id, nik, tempat_lahir, tanggal_lahir, alamat, pendidikan, keahlian, status_verifikasi (pending\|verified\|rejected), tanggal_verifikasi, verifikator_id, created_at | User profile data, verification tracking |
| `kartu_kuning` | id, user_id, nomor_ak1, tanggal_cetak, qr_token, qr_code_path, qr_issued_at, expired_at, scan_count, last_scanned_at, status | Card record, QR data |
| `uploads` | id, user_id, file_path, jenis_dokumen (ktp\|ijazah\|foto\|kk\|sertifikat) | Uploaded documents |
| `notifications` (custom, not Laravel's) | id, user_id, judul, pesan, tipe, status_baca, created_at | In-app notifications (custom model) |
| `status_pekerjaan` | id, user_id, status_pekerjaan, nama_perusahaan, tanggal_update, created_at | Job status updates by user |
| `personal_access_tokens` | id, tokenable_id, token, abilities, created_at | API tokens (Laravel Sanctum) |

**Relationships**: `User` → `Biodata` (1:1), `User` → `KartuKuning` (1:1), `User` → `Upload` (1:N), `User` → `NotificationE` (1:N), `User` → `StatusPekerjaan` (1:1).

## Queue & Jobs Explained

**Queue Connection**: `database` (no Redis needed). Jobs stored in `jobs` table.

**Active Jobs**:

| Job | Triggered By | Behavior | Timing |
|-----|-------------|----------|--------|
| `SendSmartReminders` | Scheduled daily at 07:00 via `schedule:work` | Finds users with stale status pekerjaan (6+ months) & kartu expiring in 30 days | Every day 07:00 |
| `SendBiodataStatusNotification` (event listener) | `BiodataStatusChanged` event (admin verifies) | Creates in-app notification for user | Immediate |

**How to run queue locally**:
```bash
php.bat artisan queue:work   # Process jobs immediately (blocks terminal)
# Or in dev: composer run dev  # Includes queue worker automatically
```

**Where jobs are defined**:
- `/app/Jobs` — job class definitions
- `/app/Events` & `/app/Listeners` — event-driven jobs

## Security Highlights

- **QR Verification**: Public `/verify/{nomor_ak1}?token={token}` endpoint does NOT require login. Token is HMAC-SHA256 signed; cannot be forged without APP_KEY.
- **QR Throttling**: Verification endpoint throttled to 30 requests/minute per IP to prevent brute-force scanning.
- **File Access Control**: Uploaded files in `storage/app/public/uploads/` are readable only to the owning user and admin. No direct URL access; served via `UploadController@preview` with auth checks.
- **2-Year QR Expiry**: Tokens expire 2 years after Kartu generation. Expired tokens show "Kartu Expired" on public verification page.
- **HMAC Signing**: QR token = `hash_hmac('sha256', {user_id}:{nomor_ak1}:{timestamp}, APP_KEY)`. Prevents tampering without knowing APP_KEY.
- **CSP - 'unsafe-eval'**: `'unsafe-eval'` wajib ada di `script-src` karena Alpine.js v3. Di-set permanent di `SecurityHeaders.php` (tidak conditional `isLocal()`). Font Awesome CDN (`https://cdnjs.cloudflare.com`) di-whitelist di `style-src` dan `font-src`.
- **QR verify photo**: Halaman publik `/verify/{nomor_ak1}` mengirim foto profil sebagai base64 data URI (`$photoBase64`) untuk menghindari route `profile.photo` yang butuh auth.
- **Upload redirect**: `UploadController@store` pakai `Validator::make()` manual (bukan `$request->validate()`) agar bisa redirect ke `profile.edit#berkas` dengan `withErrors()`. `$request->validate()` throw `ValidationException` yang redirect ke URL sebelumnya tanpa hash.

## Common Development Patterns

### Adding a New In-App Notification Type
1. Define event in `/app/Events` (e.g., `MyCustomEvent`)
2. Define listener in `/app/Listeners` (e.g., `SendMyCustomNotification`)
3. Register listener in `app/Providers/AppServiceProvider.php`:
   ```php
   Event::listen(
       MyCustomEvent::class,
       SendMyCustomNotification::class
   );
   ```
4. In listener, create `NotificationE` record:
   ```php
   NotificationE::create([
       'user_id' => $this->event->userId,
       'judul' => 'Judul',
       'pesan' => 'Pesan',
       'tipe' => 'status_update',
   ]);
   ```

### Extending Biodata Form Fields
1. Add migration in `/database/migrations` (e.g., `add_new_field_to_biodata`)
2. Update `Biodata` model fillable array: `protected $fillable = [..., 'new_field']`
3. Add form input to `/resources/views/user/profile.blade.php` in Biodata section
4. Update validation in `BiodataRequest` form request: `new_field' => 'required|...'`

### Creating Admin Report Filters
1. Reports use `pandeglang_wilayah.json` for district/village dropdowns
2. Reports use `DashboardController@laporanAdmin` (renders view), `@buildLaporanQuery` (private helper builds query with filters like `kecamatan_code`, `kelurahan_name`, `status_pekerjaan`)
3. Build query: `Biodata::when($filters['status'], fn($q) => $q->where('status_verifikasi', $filters['status']))->get()`
4. Export: Use `exportLaporanCsv()` / `exportLaporanPdf()` in controller



| What | Command |
|---|---|
| Dev server (all) | `composer run dev` (PHP server + queue + pail + Vite) |
| Quick server only | `php.bat artisan serve` |
| Queue worker | `php.bat artisan queue:work` |
| Scheduler | `php.bat artisan schedule:work` |
| Run all tests | `composer run test` (runs `config:clear` first) |
| Single test | `php.bat artisan test --compact --filter={Name}` |
| Format code | `vendor/bin/pint --dirty` |
| Clear Blade cache | `php.bat artisan view:clear` |
| Seed demo | `php.bat artisan db:seed --class=DemoDataSeeder` |

**Always use `php.bat` not `php`** (PHP is not in PATH; wrapper provided).

> **Production note**: `php.bat artisan serve` spawns a child `php -S` process that does **not** inherit `-d` flags. In production (`start-container.sh`), `php -S` is called directly with `-d upload_max_filesize=10M -d post_max_size=12M`. Without this, uploads >1MB will fail silently at PHP level before Laravel validation runs.

## Pre-seeded accounts (password: `password`)

| Email | Role |
|---|---|
| `user@ekaku.test` | User |
| `admin@ekaku.test` | Admin |
| `atasan@ekaku.test` | Atasan |

Set `SEED_DEMO_DATA=true` in `.env` to auto-seed on migrate.

## Testing

- Pest with `RefreshDatabase` + SQLite in-memory
- Key test: `Ak1WorkflowTest` (full registration → biodata → uploads → admin verify → kartu kuning)
- Single test: `php.bat artisan test --compact --filter={TestName}`

## Architecture

- **Roles**: `user`, `admin`, `atasan` (middleware `role:{name}`)
- **Auth**: Laravel Breeze (Blade/Alpine), routes in `routes/auth.php`
- **Routes**: All user routes in `routes/web.php` under `auth` middleware
- **Profile page**: `resources/views/user/profile.blade.php` — single-page tab layout (akun/biodata/berkas) using Alpine `x-show` + hash routing in `x-init` with `setTimeout`
- **Upload types**: ktp (required), ijazah (required), foto (required), kk, sertifikat
- **Wilayah data**: `database/data/pandeglang_wilayah.json` for admin report filters
- **Notifications**: Custom `NotificationE` model (not Laravel's built-in notifications)

## Profile page gotchas

- The sidebar `<div class="space-y-6">` must be a **grid sibling** of the `lg:col-span-2` column inside the `lg:grid-cols-3` grid — NOT nested inside the column. If the sidebar moves below the content, the column div wasn't properly closed.
- Tab switching uses `x-init` with `setTimeout(() => { activeTab = ... }, 50)` reading `window.location.hash`. Inline ternary in `x-data` does NOT work reliably for this.
- Biodata container (`#biodata`) uses `x-show="activeTab !== 'akun'"` so both biodata AND berkas tabs show it. The gradient header + p-6 form inside hide via `x-show="activeTab === 'biodata'"`.

## Frontend structure

- Tab switching logic: `<div x-data="{ activeTab: 'akun', openPribadi: true, openPendidikan: true }" x-init="setTimeout(() => activeTab = window.location.hash === '#biodata' ? 'biodata' : window.location.hash === '#berkas' ? 'berkas' : 'akun', 50)">`
- Single-page design with Alpine.js reactive state management

## Dev environment setup

```bash
composer run setup  # runs: composer install, key:generate, migrate, npm install, build
```

Or step-by-step:

```bash
composer install
php.bat artisan key:generate
php.bat artisan migrate
npm install
npm run build
```

## Testing workflow

1. Run all tests: `composer run test`
2. Single test: `php.bat artisan test --compact --filter={TestName}`
3. Format before committing: `vendor/bin/pint --dirty`

## Important files

- `routes/web.php:44` - Main user route group (auth middleware)
- `routes/auth.php` - Laravel Breeze auth routes
- `resources/views/user/profile.blade.php:23` - Profile page with Alpine.js
- `database/seeders/DemoDataSeeder.php:14` - Demo data seeder (300+ users)
- `tests/Feature/Ak1WorkflowTest.php:16` - Key integration test

## LSP false positives

LSP parser errors in `.blade.php` files from CSS/SCSS inside `<style>` blocks are pre-existing and harmless. Ignore them.

## MCP tools available

- `laravel-boost` (Artisan `boost:mcp`) — tinker, routes, config, schema, docs search, browser logs
- `laravel-telescope` (Artisan `telescope-mcp:server`) — debug queries, requests, logs, exceptions, etc. Requires `APP_ENV=local` or `TELESCOPE_ENABLED=true`.
- `lighthouse` (`lighthouse-mcp`) — Lighthouse performance & accessibility audits
- `filesystem` (`@modelcontextprotocol/server-filesystem`) — full filesystem read/write access to project directory

## Deployment

Railway via `nixpacks.toml` (Nixpacks builder, not Docker). `Procfile` defines 3 processes: `web` (bash start-container.sh), `worker` (queue:work), `cron` (schedule:work).

**Key config**:
- `start-container.sh` entrypoint: `exec php -d upload_max_filesize=10M -d post_max_size=12M -S 0.0.0.0:${PORT:-8000} -t public`
- Railway Volume `storage` (500MB) mounted at `/app/storage/app/public` — uploads persistent across redeploy.
- `APP_DEBUG=false` dan `APP_ENV=production` diset via Railway env vars (bukan `.env` file).
- Demo accounts (admin/user/atasan@ekaku.test, password `password`) dibuat via `php artisan tinker` heredoc di `start-container.sh`, bukan seeder.
- `php artisan storage:link` + `migrate --force` jalan otomatis tiap startup. `optimize:clear && optimize` juga jalan.
- Session/Cache/Queue semuanya pakai `database` driver.
- Lihat `DEPLOYMENT.md` untuk detail lebih lanjut.

## Project conventions

- **File paths**: Always use `php.bat` wrapper (PHP not in PATH)
- **Testing**: `RefreshDatabase` trait for isolated test state
- **Code style**: `vendor/bin/pint --dirty` for formatting before commits
