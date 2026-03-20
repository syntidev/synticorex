# Fase 1: Compilar el Frontend (Tailwind 4)
FROM node:22-alpine AS frontend
WORKDIR /app
COPY . .
RUN npm install
RUN npm run build

# Fase 2: Servidor Laravel
FROM serversideup/php:8.3-fpm-apache
WORKDIR /var/www/html
COPY --from=frontend /app /var/www/html
RUN composer install --no-dev --optimize-autoloader