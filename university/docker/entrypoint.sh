#!/bin/bash

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction
fi

cp .env.example .env

role=${CONTAINER_ROLE:-app}

if  [ "$role" == "app" ]; then
    echo "Laravel app starting ..."
    php artisan optimize
    php artisan migrate:fresh
    php artisan test
    php artisan db:seed
    php-fpm -D
    nginx -g "daemon off;"
elif [ "$role" == "queue" ]; then
    echo "Laravel queue starting ..."

    php artisan queue:restart
    php artisan queue:work --tries=3 --timeout=1s
fi