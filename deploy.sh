#!/bin/bash

PHP_FPM=php8.5-fpm

cd $SITE_PATH

php artisan down --refresh=5

git pull origin $BRANCH

composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service $PHP_FPM reload ) 9>/tmp/fpmlock

php artisan queue:restart

php artisan migrate --force

php artisan optimize:clear
php artisan optimize

npm ci
npm run build

php artisan up