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

php artisan optimize:clear 2>/dev/null || true
php artisan optimize 2>/dev/null || true

exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
