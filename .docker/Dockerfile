FROM php:7.4-fpm

COPY composer.lock composer.json /var/www/

WORKDIR /var/www

# Add the Node 12 repository
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libxpm-dev \
    libwebp-dev \
    libfreetype6-dev \
    nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-configure zip \
    && docker-php-ext-configure gd \
          --with-freetype --with-jpeg --with-xpm --with-webp \
    && docker-php-ext-install zip pdo_mysql gd exif bcmath

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]