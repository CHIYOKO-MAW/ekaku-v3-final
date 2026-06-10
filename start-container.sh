#!/bin/bash
set -e

php artisan storage:link
php artisan optimize:clear
php artisan optimize

exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
