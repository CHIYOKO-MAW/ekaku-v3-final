#!/bin/bash

# Alias Railway DATABASE_URL to Laravel DB_URL
if [[ -z "$DB_URL" && -n "$DATABASE_URL" ]]; then
  export DB_URL="$DATABASE_URL"
fi

php artisan storage:link 2>/dev/null || true
php artisan migrate --force 2>/dev/null || true
php artisan optimize:clear 2>/dev/null || true
php artisan optimize 2>/dev/null || true

exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
