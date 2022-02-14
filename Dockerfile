FROM php:7.4-apache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get update && apt-get install -y git libzip-dev unzip sendmail libpng-dev libonig-dev \
    && docker-php-ext-install zip gd pdo pdo_mysql mbstring mysqli \
    && a2enmod rewrite headers

WORKDIR /var/www/html

RUN composer install

RUN mkdir -p /var/www/html/var/cache

RUN chmod -R 777 /var/www/html/var/cache

RUN mkdir -p /var/www/html/var/log

RUN chmod -R 777 /var/www/html/var/log
