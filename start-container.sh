#!/bin/bash
set -e

# Alias Railway DATABASE_URL to Laravel DB_URL (config/database.php reads env('DB_URL'))
if [[ -z "$DB_URL" && -n "$DATABASE_URL" ]]; then
  export DB_URL="$DATABASE_URL"
fi

php artisan storage:link
php artisan migrate --force
php artisan optimize:clear
php artisan optimize

exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
