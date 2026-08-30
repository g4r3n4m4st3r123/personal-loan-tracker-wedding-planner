#!/bin/sh

set -e

echo "Starting Laravel application..."

php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "Running database migrations..."

php artisan migrate --force

echo "Creating storage link..."

php artisan storage:link || true

echo "Starting services..."

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf