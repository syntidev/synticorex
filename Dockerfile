# Usamos Node 22 para que Tailwind 4 vuele
FROM node:22-alpine AS build
WORKDIR /app
COPY . .
RUN npm install
RUN npm run build

# Usamos la imagen oficial de PHP para Laravel
FROM serversideup/php:8.3-fpm-apache
WORKDIR /var/www/html
COPY --from=build /app /var/www/html
RUN composer install --no-dev --optimize-autoloader