FROM php:8.3-apache

RUN docker-php-ext-install intl gd zip

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y composer
RUN composer install --optimize-autoloader --no-scripts --no-interaction

RUN a2enmod rewrite