FROM php:8.3-fpm-bullseye

RUN apt-get update && apt-get install -y --no-install-recommends \
    libicu-dev zlib1g-dev libjpeg-dev libfreetype6-dev libzip-dev libpq-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl gd zip pdo pdo_pgsql

WORKDIR /app

COPY composer.json composer.lock ./

RUN curl -fsSL https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader --no-scripts --no-interaction \
    && composer clear-cache

COPY . .

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

CMD ["/entrypoint.sh"]