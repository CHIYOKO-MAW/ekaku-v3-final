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
for d in vendor/laravel/pail vendor/laravel/breeze \
         vendor/laravel/sail vendor/laravel/roster \
         vendor/laravel/mcp \
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
            "laravel/mcp",
            "pestphp/pest","pestphp/pest-plugin-laravel","pestphp/pest-plugin-arch",
            "nunomaduro/collision","nunomaduro/termwind","laravel/sentinel"];
    $j["packages"] = array_values(array_filter($j["packages"], function($p) use ($dev) {
        return !in_array($p["name"] ?? "", $dev);
    }));
    unset($j["packages-dev"]);
    $after = count($j["packages"]);
    file_put_contents($f, json_encode($j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    echo "[startup] installed.json: $before -> $after packages (removed " . ($before - $after) . " dev)\n";
  ' 2>&1
fi

# Regenerate autoloader without dev classes
composer dump-autoload --optimize --no-dev 2>&1 || true

# Clear ALL bootstrap cache so package:discover rebuilds clean
rm -f bootstrap/cache/services.php bootstrap/cache/packages.php bootstrap/cache/config.php bootstrap/cache/routes.php bootstrap/cache/events.php

# Laravel startup commands
php artisan migrate --force 2>&1 || true
php artisan storage:link 2>&1 || true

# Regenerate services.php with only production providers
php artisan package:discover --force 2>&1 || true

# Warm caches (config:cache reads services.php, must be clean)
php artisan config:cache 2>&1 || true
php artisan route:cache 2>&1 || true
php artisan view:cache 2>&1 || true
php artisan event:cache 2>&1 || true

# Start Nixpacks nginx + php-fpm pipeline
node /assets/scripts/prestart.mjs /app/nginx.template.conf /nginx.conf && (php-fpm -y /assets/php-fpm.conf & nginx -c /nginx.conf)
