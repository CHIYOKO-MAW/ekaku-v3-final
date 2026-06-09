# Deployment Checklist e-Kaku V3

## 1) Persiapan Environment
- PHP 8.2+ dengan extension: `mbstring`, `openssl`, `pdo`, `pdo_mysql`, `fileinfo`, `gd`, `zip`.
- Node.js 20+.
- MySQL/MariaDB.
- Web server: Nginx/Apache.

## 2) Install Dependency
```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

## 3) Konfigurasi `.env`
- Set `APP_ENV=production`
- Set `APP_DEBUG=false`
- Set `APP_URL` sesuai domain.
- Set koneksi DB (`DB_*`).
- Set `MAIL_*` untuk notifikasi email reminder.
- Gunakan driver queue non-sync (contoh: `QUEUE_CONNECTION=database`).

## 4) Setup Aplikasi
```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 5) Worker Queue & Scheduler
- Jalankan worker queue sebagai service supervisor/systemd:
```bash
php artisan queue:work --tries=3 --timeout=120
```
- Tambahkan cron scheduler Laravel:
```cron
* * * * * cd /path/to/ekaku-v3 && php artisan schedule:run >> /dev/null 2>&1
```

`reminder:smart` sudah terjadwal harian pukul `08:00`.

## 6) Smoke Test Setelah Deploy
- Login `user/admin/atasan` berhasil.
- Dashboard admin memuat statistik dan tabel.
- Filter laporan (tanggal/status/kecamatan/kelurahan) berfungsi.
- Export laporan PDF/CSV berhasil diunduh.
- Alur verifikasi: user submit biodata -> admin verify -> user bisa cetak kartu.
- Jalankan:
```bash
php artisan test
```

## 7) Catatan Operasional
- Master wilayah Pandeglang ada di `database/data/pandeglang_wilayah.json`.
- `DatabaseSeeder` hanya seed akun inti (`user/admin/atasan`) + biodata dasar.
- Seed data demo massal terpisah di `DemoDataSeeder`:
```bash
php artisan db:seed --class=DemoDataSeeder
```
- Alternatif otomatis saat `db:seed`: set `.env` -> `SEED_DEMO_DATA=true`.

## 8) Railway
### Service web
- Connect service Railway ke repo GitHub `CHIYOKO-MAW/E-KAKU-V3`.
- Railway akan baca `nixpacks.toml`.
- Build sudah disiapkan untuk PHP 8.3 + Node 20.

### Variables yang wajib diisi
- `APP_NAME=E-KAKU V3`
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=<domain railway kamu>`
- `APP_TIMEZONE=Asia/Jakarta`
- `APP_KEY=<hasil php artisan key:generate --show>`
- `DB_CONNECTION=mysql`
- `DB_HOST=<host MySQL Railway>`
- `DB_PORT=<port MySQL Railway>`
- `DB_DATABASE=<nama database Railway>`
- `DB_USERNAME=<username MySQL Railway>`
- `DB_PASSWORD=<password MySQL Railway>`
- `SESSION_DRIVER=database`
- `QUEUE_CONNECTION=database`
- `CACHE_STORE=database`
- `FILESYSTEM_DISK=public`
- `MAIL_MAILER=log` jika belum pakai SMTP
- `SEED_DEMO_DATA=false`
- Untuk MySQL, kamu bisa copy nilai dari service database Railway atau pakai reference variable ke service MySQL.

### Langkah database
1. Tambahkan plugin MySQL ke service Railway.
2. Copy nilai koneksi MySQL dari plugin ke variables web service.
3. Jalankan migrasi saat deploy. File `nixpacks.toml` sudah diset untuk `php artisan migrate --force`.

### Worker dan scheduler
- Karena queue dan cache memakai database, buat service worker terpisah jika ada job background aktif:
```bash
php artisan queue:work --tries=3 --timeout=120
```
- Scheduler Laravel juga perlu jalan tiap menit. Di Railway, buat cron/job terpisah yang menjalankan:
```bash
php artisan schedule:run
```

### Storage
- Upload dan foto profil memakai disk `public`.
- Kalau file upload harus tetap aman setelah redeploy, pakai Railway Volume atau storage eksternal. Kalau tidak, file bisa hilang saat container direfresh.
