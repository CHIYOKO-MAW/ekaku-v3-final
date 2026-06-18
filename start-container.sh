#!/bin/bash
set -e

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

# Ensure directories exist
mkdir -p storage/framework/sessions storage/framework/cache/data storage/fonts
mkdir -p /var/log/nginx /var/run /etc/nginx

# Warm caches
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true
php artisan event:cache 2>/dev/null || true

php artisan storage:link 2>/dev/null || true

# Detect PHP-FPM binary
PHP_FPM_BIN=""
for bin in php-fpm8.3 php-fpm8.4 php-fpm; do
  if command -v "$bin" >/dev/null 2>&1; then
    PHP_FPM_BIN="$bin"
    break
  fi
done

# Try nginx + php-fpm first (concurrent requests)
if command -v nginx >/dev/null 2>&1 && [ -n "$PHP_FPM_BIN" ]; then
  # Process nginx template: substitute ${PORT}
  if [ -f /app/nginx.conf ]; then
    sed "s/\${PORT}/${PORT:-8000}/g" /app/nginx.conf > /etc/nginx/nginx.conf
  fi

  # Create php-fpm run directory
  mkdir -p /var/run

  # Start PHP-FPM in background
  $PHP_FPM_BIN --daemonize --fpm-config /app/php-fpm.conf 2>&1 || true

  # Give php-fpm a moment to create the socket
  sleep 1

  # Start nginx in foreground (keeps container alive)
  exec nginx -g 'daemon off;'
fi

# Fallback: php -S (single-threaded, but works)
exec php -S 0.0.0.0:${PORT:-8000} -t public \
  -d upload_max_filesize=10M \
  -d post_max_size=12M \
  -d max_execution_time=120 \
  -d max_input_time=120 \
  -d memory_limit=256M \
  -d opcache.enable=1 \
  -d opcache.memory_consumption=128 \
  -d opcache.max_accelerated_files=10000 \
  -d opcache.validate_timestamps=0 \
  -d opcache.save_comments=0
