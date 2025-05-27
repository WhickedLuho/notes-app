FROM php:8.2-fpm

# Smarty és egyéb szükséges csomagok
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Composer telepítése globálisan
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Smarty telepítése Composerrel
WORKDIR /var/www/html
RUN composer require smarty/smarty
