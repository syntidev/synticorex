#!/bin/sh
set -e

# Crear config nginx con PORT correcto
cat > /etc/nginx/conf.d/default.conf << EOF
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
EOF

# Migraciones
php artisan migrate --force

# Iniciar php-fpm
php-fpm -D

sleep 1

# Iniciar nginx
nginx -g 'daemon off;'