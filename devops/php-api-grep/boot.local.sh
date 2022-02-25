#!/usr/bin/env bash
set -e

chown -R :www-data storage bootstrap/cache
chmod -R g+w storage bootstrap/cache

composer install --no-scripts --no-autoloader --ansi --no-interaction -d /var/www/back
composer dump-autoload -d /var/www/back

if [ ! -f ".env" ]
then
  cp .env.example .env
fi

cd /var/www/back
php artisan key:generate \
&& php artisan route:clear \
&& php artisan cache:clear \
&& php artisan config:clear

chown -R :www-data storage bootstrap/cache
chmod -R g+w storage bootstrap/cache

php-fpm
