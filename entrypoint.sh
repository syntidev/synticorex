#!/bin/sh
set -e

rm -f /etc/nginx/sites-enabled/default

cat > /etc/nginx/conf.d/app.conf << NGINXCONF
server {
    listen ${PORT:-8080};
    root /app/public;
    index index.php;
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }
}
NGINXCONF

php artisan migrate --force
php-fpm -D
sleep 1
exec nginx -g 'daemon off;'