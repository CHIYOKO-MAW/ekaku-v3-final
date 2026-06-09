# e-Kaku V3

Kartu Kuning (AK1) management — Disnaker Pandeglang, Banten.

Laravel 12, PHP 8.3, Tailwind CSS v3, Alpine.js v3.

---

## Setup (first time)

### Prerequisites

- PHP 8.3+ (laragon recommended: `C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe`)
- [Composer](https://getcomposer.org)
- [Node.js](https://nodejs.org) 20+
- [Laragon](https://laragon.org) or XAMPP with MySQL (optional, SQLite works for dev)

### Quick setup

```bash
composer run setup
```

This runs: `composer install` → create `.env` → `php artisan key:generate` → `php artisan migrate` → `npm install` → `npm run build`.

If you prefer step by step:

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy environment file
copy .env.example .env

# 3. Generate app key
php.bat artisan key:generate

# 4. Run migrations (default SQLite)
php.bat artisan migrate

# 5. Install & build frontend
npm install
npm run build
```

> **Note:** On Windows, use `php.bat` instead of `php` (PHP is not in PATH, wrapper provided).

### Database

Default uses **SQLite** (`database/database.sqlite`). No setup needed.

For **MySQL**, edit `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ekaku-v3
DB_USERNAME=root
DB_PASSWORD=
```

### Seed demo data

```bash
php.bat artisan db:seed --class=DemoDataSeeder
```

Or set in `.env`:
```
SEED_DEMO_DATA=true
```
Then re-run `php artisan db:seed`.

Pre-seeded accounts (password: `password`):

| Email | Role |
|-------|------|
| `user@ekaku.test` | User |
| `admin@ekaku.test` | Admin |
| `atasan@ekaku.test` | Atasan |

---

## Dev server

```bash
composer run dev
```

Starts everything at once: PHP server (`http://localhost:8000`) + queue worker + logs (`pail`) + Vite hot reload.

Or run individually:

```bash
php.bat artisan serve          # dev server
php.bat artisan queue:work     # queue (required for reminders)
php.bat artisan schedule:work  # scheduler (required for daily reminders)
npm run dev                    # Vite HMR
```

---

## Testing

```bash
composer run test
```

Also resets config before running. Single test:

```bash
php.bat artisan test --compact --filter={TestName}
```

Uses Pest with SQLite in-memory (`RefreshDatabase`). Key test: `Ak1WorkflowTest`.

---

## Format code

```bash
vendor/bin/pint --dirty
```

Run before finalizing changes.

---

## Deployment

See [`DEPLOYMENT.md`](DEPLOYMENT.md) for production checklist.

Railway config via [`nixpacks.toml`](nixpacks.toml) + [`Procfile`](Procfile).
Session, queue, cache default to `database` driver. Worker + cron defined in `Procfile`.

---

## Wilayah data

`database/data/pandeglang_wilayah.json` — master wilayah Pandeglang for admin report filters.

---

## License

[MIT](LICENSE)
