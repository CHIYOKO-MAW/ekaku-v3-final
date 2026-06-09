#!/bin/bash
set -e

if [ "$RAILPACK_SKIP_MIGRATIONS" != "true" ]; then
    php artisan migrate --force
fi

php artisan storage:link
php artisan optimize:clear
php artisan optimize

exec docker-php-entrypoint --config /Caddyfile --adapter caddyfile 2>&1
