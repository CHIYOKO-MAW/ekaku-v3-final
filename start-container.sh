#!/bin/bash
set -e

# Parse Railway DATABASE_URL to Laravel DB_* env vars if present
if [[ -n "$DATABASE_URL" ]]; then
  DB_URI_REGEX='^([a-z]+)://([^:]+):([^@]+)@([^:]+):([0-9]+)/(.+)$'
  if [[ "$DATABASE_URL" =~ $DB_URI_REGEX ]]; then
    export DB_CONNECTION="${BASH_REMATCH[1]}"
    export DB_USERNAME="${BASH_REMATCH[2]}"
    export DB_PASSWORD="${BASH_REMATCH[3]}"
    export DB_HOST="${BASH_REMATCH[4]}"
    export DB_PORT="${BASH_REMATCH[5]}"
    export DB_DATABASE="${BASH_REMATCH[6]}"
  fi
fi

php artisan storage:link
php artisan migrate --force
php artisan optimize:clear
php artisan optimize

exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
