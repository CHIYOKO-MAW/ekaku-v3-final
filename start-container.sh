#!/bin/bash

# Set Laravel DB_* from Railway DATABASE_URL or MYSQL_* env vars
if [[ -n "$DATABASE_URL" ]]; then
  export DB_CONNECTION=mysql
  export DB_URL="$DATABASE_URL"
elif [[ -n "$MYSQL_URL" ]]; then
  export DB_CONNECTION=mysql
  export DB_URL="$MYSQL_URL"
elif [[ -n "$MYSQL_HOST" ]]; then
  export DB_CONNECTION=mysql
  export DB_HOST="$MYSQL_HOST"
  export DB_PORT="${MYSQL_PORT:-3306}"
  export DB_DATABASE="${MYSQL_DATABASE:-railway}"
  export DB_USERNAME="${MYSQL_USER:-railway}"
  export DB_PASSWORD="$MYSQL_PASSWORD"
fi

php artisan storage:link 2>/dev/null || true
php artisan migrate --force 2>/dev/null || true

# Create demo accounts if they don't exist
php artisan tinker <<'EOT' 2>/dev/null || true
$users = [
  ['name' => 'Demo Admin', 'email' => 'admin@ekaku.test', 'role' => 'admin'],
  ['name' => 'Demo User',  'email' => 'user@ekaku.test',  'role' => 'user'],
  ['name' => 'Demo Atasan','email' => 'atasan@ekaku.test','role' => 'atasan'],
];
foreach ($users as $u) {
  if (!\App\Models\User::where('email', $u['email'])->exists()) {
    \App\Models\User::create($u + ['password' => bcrypt('password'), 'email_verified_at' => now()]);
    echo 'Created: ' . $u['email'] . PHP_EOL;
  }
}
EOT

# Warm caches at startup — avoid per-request overhead
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
# php artisan view:cache conflicts with Blaze::optimize — let Blaze handle compilation
# php artisan view:cache 2>/dev/null || true
php artisan event:cache 2>/dev/null || true

# Ensure public disk link (idempotent)
php artisan storage:link 2>/dev/null || true

exec php -d upload_max_filesize=10M -d post_max_size=12M -d max_execution_time=120 -d max_input_time=120 -d memory_limit=256M -d zlib.output_compression=On -d opcache.enable=1 -d opcache.memory_consumption=128 -d opcache.max_accelerated_files=10000 -d opcache.validate_timestamps=0 -d opcache.revalidate_freq=0 -d opcache.save_comments=0 -S 0.0.0.0:${PORT:-8000} -t public
