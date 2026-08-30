#!/bin/sh

set -e

echo "Preparing Laravel..."

php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Running migrations..."

php artisan migrate --force

echo "Creating storage link..."

php artisan storage:link || true

echo "Starting PHP-FPM and NGINX..."

exec /usr/bin/supervisord \
    -c /etc/supervisor/conf.d/supervisord.conf