#!/bin/bash

# Debug: check Railway MySQL env vars via PHP (guaranteed to appear in logs)
php -r "
echo 'MYSQL_HOST=[' . (getenv('MYSQL_HOST') ?: '') . ']\n';
echo 'MYSQL_PORT=[' . (getenv('MYSQL_PORT') ?: '') . ']\n';
echo 'MYSQL_DATABASE=[' . (getenv('MYSQL_DATABASE') ?: '') . ']\n';
echo 'MYSQL_USER=[' . (getenv('MYSQL_USER') ?: '') . ']\n';
echo 'MYSQL_PASSWORD=[' . (getenv('MYSQL_PASSWORD') ? 'SET' : '') . ']\n';
echo 'MYSQL_URL=[' . (getenv('MYSQL_URL') ?: '') . ']\n';
echo 'DATABASE_URL=[' . (getenv('DATABASE_URL') ?: '') . ']\n';
echo 'DB_URL=[' . (getenv('DB_URL') ?: '') . ']\n';
"

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
