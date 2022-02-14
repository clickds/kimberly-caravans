FROM php:8.0-apache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get update && apt-get install -y git libzip-dev unzip sendmail libpng-dev libonig-dev \
    && docker-php-ext-install zip gd pdo pdo_mysql mbstring mysqli \
    && a2enmod rewrite headers
