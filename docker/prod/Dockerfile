FROM php:7.4.3-fpm

WORKDIR "/app"

RUN apt-get update && apt-get install -y zip unzip libpq-dev postgresql-client

RUN docker-php-ext-install pdo_pgsql

RUN apt-get update && apt-get install -y \
    libzip-dev \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install opcache \
    && echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/php.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/php.ini \
    && echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/php.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/php.ini \
    && echo "opcache.preload_user=www-data" >> /usr/local/etc/php/conf.d/php.ini \
    && echo "opcache.preload=/app/var/cache/prod/App_KernelProdContainer.preload.php" >> /usr/local/etc/php/conf.d/php.ini \
    && echo "realpath_cache_size=4096K" >> /usr/local/etc/php/conf.d/php.ini \
    && echo "realpath_cache_ttl=3600" >> /usr/local/etc/php/conf.d/php.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version
# composer install --optimize-autoloader --no-dev --classmap-authoritative --> ~200ms faster when no opcache enabled