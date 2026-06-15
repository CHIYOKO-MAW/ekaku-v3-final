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

# Warm caches after env vars are set
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true
php artisan event:cache 2>/dev/null || true

php artisan storage:link 2>/dev/null || true

exec frankenphp run --config /app/Caddyfile
