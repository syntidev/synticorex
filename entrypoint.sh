#!/bin/sh
set -e

# Sustituir PORT en nginx config
envsubst '${PORT}' < /etc/nginx/sites-enabled/default > /tmp/nginx.conf

# Correr migraciones
php artisan migrate --force

# Iniciar php-fpm
php-fpm -D

sleep 1

# Iniciar nginx
nginx -c /tmp/nginx.conf -g 'daemon off;'