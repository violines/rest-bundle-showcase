FROM php:8.0-fpm

WORKDIR "/app"

RUN apt-get update && apt-get install -y zip unzip libpq-dev postgresql-client

RUN docker-php-ext-install pdo_pgsql

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.client_port=9000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=\${XDEBUG_IDE_KEY}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && docker-php-source delete

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer