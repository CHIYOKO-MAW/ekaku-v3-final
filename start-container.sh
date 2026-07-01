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

# === FIX: Nixpacks COPY . /app can overwrite vendor/ with dev packages ===
# Always clean installed.json and strip dev directories on every startup.
# Cost: ~0.5s one PHP command. Benefit: production-only providers in services.php.

# Remove known dev package directories (silently ignore if absent)
# NOTE: Do NOT delete vendor/laravel/mcp — config/ai.php or cached configs may reference it.
# It's already in dont-discover so its provider is never registered.
for d in vendor/laravel/pail vendor/laravel/breeze \
         vendor/laravel/sail vendor/laravel/roster \
         vendor/pestphp \
         vendor/nunomaduro/collision; do
  rm -rf "$d" 2>/dev/null
done

# Strip dev packages from installed.json so package:discover only finds production providers
if [ -f vendor/composer/installed.json ]; then
  php -r '
    $f = "vendor/composer/installed.json";
    $j = json_decode(file_get_contents($f), true);
    if (!isset($j["packages"])) { echo "no packages key\n"; exit(0); }
    $before = count($j["packages"]);
    $dev = ["laravel/pail","laravel/breeze","laravel/sail",
            "laravel/roster",
            "pestphp/pest","pestphp/pest-plugin-laravel","pestphp/pest-plugin-arch",
            "nunomaduro/collision"];
    $j["packages"] = array_values(array_filter($j["packages"], function($p) use ($dev) {
        return !in_array($p["name"] ?? "", $dev);
    }));
    unset($j["packages-dev"]);
    $after = count($j["packages"]);
    file_put_contents($f, json_encode($j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    echo "[startup] installed.json: $before -> $after packages (removed " . ($before - $after) . " dev)\n";
  ' 2>&1
fi

# Clear ALL bootstrap cache BEFORE dump-autoload
# (post-autoload-dump triggers package:discover which loads cached config)
rm -f bootstrap/cache/services.php bootstrap/cache/packages.php bootstrap/cache/config.php bootstrap/cache/routes.php bootstrap/cache/events.php

# Regenerate autoloader
composer dump-autoload --optimize 2>&1 || true

# Laravel startup commands
php artisan migrate --force 2>&1 || true
php artisan storage:link 2>&1 || true

# Regenerate services.php with only production providers
php artisan package:discover 2>&1 || true

# Clear old caches, then warm fresh ones
php artisan config:clear 2>&1 || true
php artisan route:clear 2>&1 || true
php artisan view:clear 2>&1 || true
php artisan event:clear 2>&1 || true
php artisan responsecache:clear 2>&1 || true
# Clear file-based response cache (spatie/laravel-responsecache uses 'file' driver)
rm -rf storage/framework/cache/data/* 2>/dev/null || true

php artisan config:cache 2>&1 || true
php artisan route:cache 2>&1 || true
php artisan view:cache 2>&1 || true
php artisan event:cache 2>&1 || true

# Generate nginx config from template
node /assets/scripts/prestart.mjs /app/nginx.template.conf /nginx.conf

# Start php-fpm in background
php-fpm -y /assets/php-fpm.conf &

# Pre-warm caches in background — makes first user visit instant
(
  for i in 1 2 3 4 5 6 7 8 9 10; do
    sleep 1
    curl -sf http://localhost:${PORT}/ > /dev/null 2>&1 && break
  done
  curl -sf http://localhost:${PORT}/ > /dev/null 2>&1
  curl -sf http://localhost:${PORT}/login > /dev/null 2>&1
  echo "[warmup] Guest pages cached."
) &

# Start nginx in foreground — keeps container alive
nginx -c /nginx.conf
