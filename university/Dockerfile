# Used for prod build.
FROM php:8.2-fpm as php

ENV PHP_OPCACHE_ENABLE=1
ENV PHP_OPCACHE_ENABLE_CLI=0
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=1
ENV PHP_OPCACHE_REVALIDATE_FREQ=1

RUN usermod -u 1000 www-data

RUN apt-get update -y
RUN apt-get install -y unzip libpq-dev libcurl4-gnutls-dev nginx
RUN docker-php-ext-install pdo pdo_mysql bcmath curl opcache
RUN pecl install -o -f redis \
      && rm -rf /tmp/pear \
      && docker-php-ext-enable redis

WORKDIR /var/www

COPY --chown=www-data . . 

COPY ./docker/php.ini /usr/local/etc/php/php.ini
COPY ./docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/nginx.conf /etc/nginx/nginx.congs

COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer

RUN chmod -R 755 /var/www/storage
RUN chmod -R 755 /var/www/bootstrap

ENTRYPOINT [ "docker/entrypoint.sh" ]