#!/bin/bash


cd /var/www/html/
composer install

php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan migrate
y | php artisan passport:install --uuids
php artisan db:seed
php artisan queue:restart
php artisan l5-swagger:generate

# Exit immediately if a command exits with a non-zero status.
set -e

# Start cron
cron

# Execute CMD
exec "$@"
