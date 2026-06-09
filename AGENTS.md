# e-Kaku V3

Laravel 12 — Kartu Kuning (AK1) management app for Pandeglang, Banten.

## Roles & seed accounts

Three roles: `user`, `admin`, `atasan`. Pre-seeded (password: `password`):
- `user@ekaku.test`
- `admin@ekaku.test`
- `atasan@ekaku.test`

Seed: `php artisan db:seed --class=DemoDataSeeder` or set `SEED_DEMO_DATA=true` in `.env` and run `db:seed`.

## Dev commands

| Command | What it does |
|---|---|
| `composer run dev` | Full dev environment (PHP server + queue + logs + Vite via concurrently) |
| `composer run test` | Clears config then runs `php artisan test` |
| `vendor/bin/pint --dirty` | Format code (run before finalizing changes) |
| `php artisan test --compact --filter={testName}` | Run single test |
| `npm run build` | Build frontend assets |

## Architecture

Custom `role` middleware (`App\Http\Middleware\RoleMiddleware`) registered as alias in `bootstrap/app.php`. Route groups use `->middleware('role:admin')`, `'role:user'`, `'role:atasan'`. Accepts comma-separated roles (e.g. `'role:admin,atasan'`).

Middleware, routing, and exceptions configured in `bootstrap/app.php` (Laravel 12 style — no `app/Http/Kernel.php`). Service providers in `bootstrap/providers.php`.

Wilayah data: `database/data/pandeglang_wilayah.json` (Pandeglang district/village hierarchy).

No API routes (`routes/api.php` does not exist). Only `routes/web.php`, conditionally `routes/auth.php`, and `routes/console.php`.

## Models & SoftDeletes

**All** models use `SoftDeletes`: User, Biodata, KartuKuning, Upload, NotificationE, StatusPekerjaan. Queries that need deleted records must use `withTrashed()`.

- `User`: `hasCompletedProfile()` (biodata fields), `hasRequiredDocuments()` (ktp/ijazah/foto uploads), `generateQrCode()` (HMAC-SHA256 over `user_id:nomor_ak1:issuedAt` using `APP_KEY`, stores `qr_token`, `qr_issued_at`, `expired_at` +2yrs)
- `KartuKuning`: status enum `pending|printed|expired`, `scan_count` unsigned int default 0 (increments only on successful public verification in `verify()`)
- `NotificationE`: maps to `notifications` table (not `notification_e`), `tipe` enum `status_update|kartu_expired`, `status_baca` boolean

## Reminder system

Single `SendSmartReminders` Job runs daily at 07:00 via `routes/console.php`. Enhances old `reminder:smart` command (removed).

Trigger: `Schedule::job(SendSmartReminders)->dailyAt('07:00')`. Delivery uses both Laravel `Notification` (email) and `NotificationE` model (in-app). Includes dedup (7-day cooldown for status/kartu, 30-day for expired) via `NotificationE` table.

| Check | Logic |
|---|---|
| Kartu expiry (30 days before) | `expired_at` within next 30 days, status=printed |
| Kartu already expired | `expired_at` in past, status=printed → auto-set to `expired` |
| Status pekerjaan (6 months) | `status_pekerjaan.updated_at` > 6 months ago |

Requires queue worker for delivery (`php artisan queue:work`). Requires scheduler for daily trigger (`php artisan schedule:work`).

## Events

`BiodataStatusChanged` event → `SendBiodataStatusNotification` listener, registered in `AppServiceProvider::boot()` (not in `EventServiceProvider`). Also: `StatusPekerjaanReminderEvent`.

## Caching

Admin/atasan dashboard queries are cached:
- `dashboard.admin.stats` (300s), `dashboard.admin.statusDistribution` (300s), `dashboard.admin.recentActivities` (300s), `dashboard.admin.regionalSummary` (600s)
- `dashboard.atasan.stats` (300s)
- `dashboard.wilayahData` (86400s/24h)

Cleared with `php artisan cache:clear` or individually by key.

## Database indexes

Manual index migrations (beyond Laravel auto-creates):

| Migration | Table | Index |
|---|---|---|
| `add_status_verifikasi_index_to_biodata_table` | `biodata` | `status_verifikasi` |
| `add_role_index_to_users_table` | `users` | `role` |
| `add_user_id_status_baca_index_to_notifications_table` | `notifications` | `(user_id, status_baca)` |

`kartu_kuning.user_id` and `notifications.user_id` already indexed by `foreignId()->constrained()`.

## QR verification

Public route: `GET /verify/{nomor_ak1}?token=...` — no auth required. HMAC-SHA256 token using `APP_KEY`. `scan_count` incremented only on successful verification. Token valid 2 years (`expired_at`). Old `qr_code_path` file field kept for backward compat.

## Upload document types

Enum: `ktp`, `ijazah`, `foto`, `kk`, `sertifikat` (added `kk` and `sertifikat` via migration 2026_06_01_000001).

## Notifications

Two delivery systems:
1. **Laravel Notifications** (`BiodataStatusUpdated`, `KartuKuningExpiryReminder`, `StatusPekerjaanReminder`) — used by the scheduled job and biodata verification
2. **NotificationE model** (table `notifications`) — used by manual `reminder:smart` command and manual admin sends. `status_baca` boolean, `tipe` enum.

## Key dependencies

- `barryvdh/laravel-dompdf` — PDF (kartu kuning, laporan)
- `simplesoftwareio/simple-qrcode` — QR code
- `laravel/boost` — MCP server (`artisan boost:mcp`)
- Alpine.js v3 + Tailwind CSS v3 — frontend
- chart.js, html5-qrcode, sweetalert2 — JS dependencies

## Testing

Pest with `RefreshDatabase`, SQLite in-memory. Tests in `tests/Feature/`. Key test: `Ak1WorkflowTest` — full flow (register → biodata → upload → submit → admin verify → kartu kuning PDF).

## Deployment

Railway via `nixpacks.toml` (PHP 8.3 + Node 20). See `DEPLOYMENT.md`. Session, queue, cache default to `database` driver.

## PHP & Boost

PHP via Laragon at `C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe`. Not in system PATH — use `php.bat` wrapper (project root). All `php`/`artisan`/`composer` commands assume `php.bat`. Boost MCP (`artisan boost:mcp`) configured in `opencode.json`.

Local `.env` uses `DB_CONNECTION=sqlite` — switch to `mysql` for production.

## Conventions

- PHP 8.3, typed properties/returns, constructor property promotion
- Custom `disnaker` color palette in Tailwind (`disnaker-500: #005aa7`)
- `NotificationE` model (avoids collision with Laravel's `Notification`)
- QR verification: HMAC-SHA256 token, public `/verify/{nomor_ak1}`
- Blade views grouped by role (`admin/`, `atasan/`, `user/`)
- CSV exports: UTF-8 BOM, ISO dates
- Profile photos: `profile_photo_path` column, served via `route('profile.photo')`, accessor `getProfilePhotoUrlAttribute()`
- `composer run setup` — full first-time setup

## Frontend styling

- Cards: `card-shadow` class (`rounded-xl bg-white p-6 card-shadow`), never `rounded-lg bg-white shadow`
- Buttons: `btn-primary` (disnaker-500), `btn-secondary` (gray border), `btn-danger` (red-600) — defined in `resources/css/app.css`. No inline styles.
- Form fields: use `<x-input-label>`, `<x-text-input>`, `<x-input-error>` Blade components
- Empty states: table cells `<i class="fas fa-[icon] mr-2">` + centered text; standalone empty states get icon-circle pattern
- Page titles: `@section('title', ...)` in every view extending `layouts.app` or `guest-auth.blade.php`
- Alpine.js: preferred over vanilla JS (`onsubmit` → `@submit.prevent`)
- Layout `app.blade.php` uses both `{{ $slot }}` and `@yield('content')`; auto-selects nav partial by role (`layouts.navigation-{role}` or `navigation-guest`); supports `@stack('styles')`/`@stack('scripts')`
- `guest-auth.blade.php` uses `$slot` only (no `@yield`), gradient background, centered card
- `app.js` disables clicked submit button on form submit, intercepts 401/419 for redirect to `/login`
