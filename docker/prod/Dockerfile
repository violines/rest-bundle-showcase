FROM php:8.0-fpm

WORKDIR "/app"

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql

RUN docker-php-ext-install opcache
ADD ./opcache.prod.ini /usr/local/etc/php/conf.d/docker-php-ext-opcache_custom.ini

RUN cd /usr/local/bin && curl -sLO https://github.com/gordalina/cachetool/releases/latest/download/cachetool.phar && chmod +x cachetool.phar && mv cachetool.phar cachetool

# composer install --optimize-autoloader --classmap-authoritative --> ~200ms faster when no opcache enabled