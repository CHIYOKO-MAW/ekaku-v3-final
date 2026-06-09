# e-Kaku V3 — AGENTS.md

## TL;DR

AK-1 Kartu Kuning (job seeker card) management for Disnaker Pandeglang. Laravel 12, Blade + Alpine.js v3, Tailwind v3, Pest. SQLite default for dev/test.

## Commands

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

See `DEPLOYMENT.md`. Railway via `nixpacks.toml` + `Procfile`. Session/queue/cache use `database` driver.

## Project conventions

- **File paths**: Always use `php.bat` wrapper (PHP not in PATH)
- **Database**: SQLite default, MySQL optional via `.env` config
- **Testing**: `RefreshDatabase` trait for isolated test state
- **Authentication**: Multi-role system with middleware `role:{name}`
- **Notifications**: Custom `NotificationE` model (not Laravel's built-in)
- **Frontend**: Alpine.js v3 for reactive UI with x-show directives
- **Code style**: `vendor/bin/pint --dirty` for formatting before commits
