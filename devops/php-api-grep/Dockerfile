########################
### Stage - "base"
########################
FROM computoolsdevops/base-php:latest as base

WORKDIR /var/www/back

RUN docker-php-ext-install \
    opcache

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="12000" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="192" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10"

# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Copy php config
COPY devops/docker-php-memlimit.ini /usr/local/etc/php/conf.d/docker-php-memlimit.ini
COPY devops/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Boot.sh
COPY devops/php-api-grep/boot.sh /usr/local/bin/php_boot.sh
RUN chmod +x /usr/local/bin/php_boot.sh

########################
### Stage - "local"
########################
FROM base as local

RUN apt-get update && apt-get install -y wget git unzip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apt-get install nano

COPY devops/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Boot.sh
COPY devops/php-api-grep/boot.local.sh /usr/local/bin/php_boot.sh
RUN chmod +x /usr/local/bin/php_boot.sh

########################
### Stage - "dev"
########################
FROM base as dev

CMD /usr/local/bin/php_boot.sh

########################
### Stage - "stage"
########################
FROM base as stage

########################
### Stage - "production"
########################
FROM base as production

WORKDIR /var/www/back

RUN apt-get update && apt-get install nano

# Boot.sh
COPY devops/php-api-grep/boot.production.sh /usr/local/bin/php_boot.sh
RUN chmod +x /usr/local/bin/php_boot.sh

COPY ./composer.json ./composer.lock* ./
# RUN composer config github-oauth.github.com YOUROAUTHKEYHERE
RUN composer install --no-scripts --no-autoloader --ansi --no-interaction

COPY --chown=www-data:www-data . .
RUN composer dump-autoload -o

RUN cp .env.example .env

# be sure nginx is properly passing to php-fpm and fpm is responding
#HEALTHCHECK --interval=5s --timeout=3s \
#  CMD curl -f http://localhost/ping || exit 1