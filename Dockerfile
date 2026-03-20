FROM php:8.3-fpm-bullseye

RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx libicu-dev zlib1g-dev libjpeg-dev libfreetype6-dev libzip-dev libpq-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl gd zip pdo pdo_pgsql

COPY vhost.conf /etc/nginx/sites-enabled/default

WORKDIR /app

COPY composer.json composer.lock ./

RUN curl -fsSL https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader --no-scripts --no-interaction \
    && composer clear-cache

COPY . .

RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx libicu-dev zlib1g-dev libjpeg-dev libfreetype6-dev libzip-dev libpq-dev gettext-base \
    && rm -rf /var/lib/apt/lists/*
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN echo "listen = 127.0.0.1:9000" >> /usr/local/etc/php-fpm.d/www.conf
EXPOSE 80

CMD ["sh", "-c", "php-fpm -D && sleep 1 && envsubst '${PORT}' < /etc/nginx/sites-enabled/default > /tmp/nginx.conf && nginx -c /tmp/nginx.conf -g 'daemon off;'"]