FROM php:8.0-fpm

WORKDIR "/app"

RUN apt-get update && apt-get install -y zip unzip libpq-dev postgresql-client

RUN docker-php-ext-install pdo_pgsql

RUN docker-php-ext-install opcache
ADD ./opcache.dev.ini /usr/local/etc/php/conf.d/docker-php-ext-opcache_custom.ini

RUN pecl install xdebug && docker-php-ext-enable xdebug && docker-php-source delete
ADD ./xdebug.dev.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug_custom.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer