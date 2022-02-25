#!/usr/bin/env bash
set -e
 
chown -R www-data:www-data storage bootstrap/cache
chmod -R 755 storage bootstrap/cache

composer install --no-scripts --no-autoloader --ansi --no-interaction -d /var/www/back
composer dump-autoload -d /var/www/back

if [ ! -f ".env" ]
then
  cp .env.example .env
fi

cd /var/www/back && php artisan key:generate
php artisan optimize && php artisan config:cache && php artisan route:cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 755 storage bootstrap/cache

php-fpm
