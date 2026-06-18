#!/bin/bash

# Ensure nginx directories exist
mkdir -p /var/log/nginx /var/cache/nginx

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

# Laravel startup commands
php artisan migrate --force 2>&1 || true
php artisan storage:link 2>&1 || true

# Clear stale bootstrap cache
rm -f bootstrap/cache/services.php bootstrap/cache/packages.php

# Regenerate clean services.php
php artisan package:discover --force 2>&1 || true

# Safety: strip any dev providers that slipped through
php clean_services.php 2>&1 || true

# Warm caches
php artisan config:cache 2>&1 || true
php artisan route:cache 2>&1 || true
php artisan view:cache 2>&1 || true
php artisan event:cache 2>&1 || true

# Start Nixpacks nginx + php-fpm pipeline
node /assets/scripts/prestart.mjs /app/nginx.template.conf /nginx.conf && (php-fpm -y /assets/php-fpm.conf & nginx -c /nginx.conf)
