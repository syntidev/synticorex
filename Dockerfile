FROM php:8.3-apache-bullseye

RUN apt-get update && apt-get install -y --no-install-recommends \
    libicu-dev zlib1g-dev libjpeg-dev libfreetype6-dev libzip-dev libpq-dev postgresql-client \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl gd zip pdo pdo_pgsql

RUN a2dismod mpm_event mpm_worker 2>/dev/null || true && a2enmod mpm_prefork rewrite

COPY vhost.conf /etc/apache2/sites-enabled/000-default.conf

WORKDIR /app

COPY composer.json composer.lock ./

RUN curl -fsSL https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader --no-scripts --no-interaction \
    && composer clear-cache

COPY . .

EXPOSE 80

CMD ["apache2-foreground"]