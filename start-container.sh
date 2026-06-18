#!/bin/bash

# Ensure nginx directories exist (Nixpacks install phase may have been overridden)
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
php artisan migrate --force 2>/dev/null || true
php artisan storage:link 2>/dev/null || true

# Clear stale bootstrap cache (services.php may contain dev-only providers)
rm -f bootstrap/cache/services.php bootstrap/cache/packages.php

# Regenerate clean services.php (exclude dev providers via dont-discover)
php artisan package:discover --force 2>/dev/null || true

# Final safety: strip any dev providers that slipped through
php -r '
$f = "bootstrap/cache/services.php";
if (file_exists($f)) {
    $m = require $f;
    $dev = ["Boost","Mcp\\Server","Telescope\\","TelescopeMcp","Pail","Sentinel\\","Collision","Termwind","Pest","Breeze","Sail","Roster","Tinker"];
    $clean = [];
    foreach ($m["providers"] ?? [] as $k => $v) {
        $skip = false;
        $str = is_array($v) ? implode($v) : (string)$v;
        foreach ($dev as $d) { if (strpos($str, $d) !== false) { $skip = true; break; } }
        if (!$skip) $clean[$k] = $v;
    }
    $m["providers"] = $clean;
    file_put_contents($f, "<?php\nreturn " . var_export($m, true) . ";\n");
    echo "Stripped " . count($m["providers"]) . " providers\n";
}
' 2>/dev/null || true

# Warm caches
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true
php artisan event:cache 2>/dev/null || true

# Start Nixpacks nginx + php-fpm pipeline
# prestart.mjs processes nginx.template.conf (substitutes ${PORT}, $if(IS_LARAVEL) etc.)
# php-fpm uses Nixpacks built-in php-fpm.conf (127.0.0.1:9000)
# nginx runs in foreground (daemon off) keeping the container alive
node /assets/scripts/prestart.mjs /app/nginx.template.conf /nginx.conf && (php-fpm -y /assets/php-fpm.conf & nginx -c /nginx.conf)
