FROM php:8.3-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    && a2enmod rewrite \
    && docker-php-ext-install zip pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Change ownership of the working directory
RUN chown -R www-data:www-data /var/www

# Use www-data as the user for Apache and PHP
USER www-data