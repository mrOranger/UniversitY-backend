#!/bin/bash

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction
fi

cp .env.example .env

php artisan optimize
php artisan migrate:fresh
php artisan test
php artisan db:seed

php-fpm -D
nginx -g "daemon off;"