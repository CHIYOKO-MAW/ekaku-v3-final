#!/bin/bash
set -e

php artisan queue:work --tries=3 --timeout=120
