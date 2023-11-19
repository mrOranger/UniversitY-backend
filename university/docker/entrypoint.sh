#!/bin/bash

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction
fi

cp .env.example .env

php artisan migrate
php artisan test
php artisan db:seed
php artisan key:generate
php artisan optimize
php artisan view:cache
php artisan serve --port=$PORT --host=0.0.0.0 --env=.env

exec docker-php-entrypoint "$@