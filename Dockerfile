### STAGE 1: builder
FROM node:18-alpine AS builder
WORKDIR /src
COPY package.json package-lock.json ./
RUN npm ci
COPY public/tailwind.css ./
RUN npx tailwindcss -i ./tailwind.css -o ./assets/css/styles.css --minify

### STAGE 2: PHP + Apache
FROM php:8.2-apache AS app
# PHP extensions
RUN apt-get update \
  && apt-get install -y git unzip libzip-dev libonig-dev libxml2-dev libicu-dev \
  && docker-php-ext-install mysqli pdo_mysql zip intl \
  && a2enmod rewrite \
  && rm -rf /var/lib/apt/lists/*
# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
# Código
COPY . .
# Config Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf
# Dependências PHP
RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader \
  && chown -R www-data:www-data /var/www/html
# Assets compilados
COPY --from=builder /src/assets/css/styles.css public/assets/css/styles.css
EXPOSE 80
CMD ["apache2-foreground"]
