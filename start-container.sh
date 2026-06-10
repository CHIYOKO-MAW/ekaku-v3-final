#!/bin/bash

# Debug: check Railway MySQL env vars
echo "MYSQL_HOST=[${MYSQL_HOST}]"
echo "MYSQL_PORT=[${MYSQL_PORT}]"
echo "MYSQL_DATABASE=[${MYSQL_DATABASE}]"
echo "MYSQL_USER=[${MYSQL_USER}]"
echo "MYSQL_PASSWORD=[${MYSQL_PASSWORD:+SET}]"
echo "MYSQL_URL=[${MYSQL_URL}]"
echo "DATABASE_URL=[${DATABASE_URL}]"
echo "DB_URL=[${DB_URL}]"

# Set Laravel DB_* from Railway DATABASE_URL or MYSQL_* env vars
if [[ -n "$DATABASE_URL" ]]; then
  export DB_URL="$DATABASE_URL"
elif [[ -n "$MYSQL_URL" ]]; then
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
php artisan optimize:clear 2>/dev/null || true
php artisan optimize 2>/dev/null || true

exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
