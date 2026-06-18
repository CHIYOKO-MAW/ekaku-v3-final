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

# === FIX: Remove dev packages from vendor/ if they leaked in ===
# Nixpacks COPY . /app at the end can overwrite clean vendor/.
# This strips dev-only directories and their composer metadata.
DEV_DIRS="vendor/laravel/telescope vendor/laravel/pail vendor/laravel/breeze vendor/laravel/sail vendor/laravel/roster vendor/lucianotonet vendor/pestphp vendor/nunomaduro/collision vendor/laravel/boost vendor/laravel/mcp"
needs_clean=false
for d in $DEV_DIRS; do
  if [ -d "$d" ]; then
    needs_clean=true
    break
  fi
done

if [ "$needs_clean" = true ]; then
  echo "[startup] Dev packages detected in vendor/, cleaning..." >&2
  for d in $DEV_DIRS; do
    rm -rf "$d" 2>/dev/null
  done

  # Also strip dev entries from installed.json so package:discover doesn't try to load them
  if [ -f vendor/composer/installed.json ]; then
    php -r '
      $f = "vendor/composer/installed.json";
      $j = json_decode(file_get_contents($f), true);
      if (isset($j["packages"])) {
        $dev = ["laravel/telescope","laravel/pail","laravel/breeze","laravel/sail","laravel/roster","laravel/boost","laravel/mcp","lucianotonet/laravel-telescope-mcp","pestphp/pest","pestphp/pest-plugin-laravel","pestphp/pest-plugin-arch","nunomaduro/collision","nunomaduro/termwind"];
        $j["packages"] = array_values(array_filter($j["packages"], function($p) use ($dev) {
          return !in_array($p["name"] ?? "", $dev);
        }));
        if (isset($j["packages-dev"])) unset($j["packages-dev"]);
        file_put_contents($f, json_encode($j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
      }
    ' 2>&1 || true
  fi

  # Regenerate autoloader without dev classes
  composer dump-autoload --optimize --no-dev 2>&1 || true
  # Clear stale cache so package:discover rebuilds clean
  rm -f bootstrap/cache/services.php bootstrap/cache/packages.php
  php artisan package:discover --force 2>&1 || true
  echo "[startup] Dev packages cleaned." >&2
else
  echo "[startup] No dev packages found, skipping cleanup." >&2
fi

# Laravel startup commands
php artisan migrate --force 2>&1 || true
php artisan storage:link 2>&1 || true

# Ensure bootstrap cache is fresh
rm -f bootstrap/cache/services.php bootstrap/cache/packages.php
php artisan package:discover --force 2>&1 || true

# Warm caches
php artisan config:cache 2>&1 || true
php artisan route:cache 2>&1 || true
php artisan view:cache 2>&1 || true
php artisan event:cache 2>&1 || true

# Start Nixpacks nginx + php-fpm pipeline
node /assets/scripts/prestart.mjs /app/nginx.template.conf /nginx.conf && (php-fpm -y /assets/php-fpm.conf & nginx -c /nginx.conf)
