#!/usr/bin/env bash
set -e


#chown -R www-data:www-data storage bootstrap/cache
#chmod -R 755 storage bootstrap/cache
#
#composer install --no-scripts --no-autoloader --ansi --no-interaction -d /var/www/back
#composer dump-autoload -d /var/www/back
#
#if [ ! -f ".env" ]
#then
#  cp .env.example .env
#fi
#
#rm -rf /var/www/back/*
#cp -a /app/. /var/www/back/

php artisan key:generate \
&& php artisan optimize:clear \
&& php artisan optimize

chown -R :www-data storage bootstrap/cache
chmod -R g+w storage bootstrap/cache

php-fpm
