FROM php:8.3-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    libicu-dev \
    zlib1g-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install intl gd zip

WORKDIR /app
COPY composer.json composer.lock ./

RUN curl -fsSL https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-dev --optimize-autoloader --no-scripts --no-interaction && \
    composer clear-cache

COPY . .

RUN a2enmod rewrite