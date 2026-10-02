# =========================
# 1. Frontend bouwen
# =========================
FROM node:20 AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .

RUN npm run build


# =========================
# 2. Laravel / PHP
# =========================
FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

# Composer beschikbaar maken
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Gebouwde Vite-bestanden uit frontend-stage
COPY --from=frontend /app/public/build ./public/build

# PHP dependencies installeren
RUN composer install \
    --no-interaction \
    --optimize-autoloader

# Laravel schrijfrechten
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Apache moet Laravel vanuit /public serveren
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri \
    -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

RUN sed -ri 's/AllowOverride None/AllowOverride All/g' \
    /etc/apache2/apache2.conf

EXPOSE 80

# Eerst database-migraties, daarna Apache starten
CMD php artisan migrate --force && php artisan db:seed --force && apache2-foreground
