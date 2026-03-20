#!/bin/sh
set -e

envsubst '${PORT}' < /etc/nginx/sites-enabled/default > /etc/nginx/conf.d/default.conf

php artisan migrate --force

php-fpm -D

sleep 1

nginx -g 'daemon off;'