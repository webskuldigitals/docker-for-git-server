# Use official PHP 8.3 base image
FROM php:8.3 AS php

# Install necessary packages and PHP extensions
RUN apt-get update -y \
    && apt-get install -y unzip libpq-dev libcurl4-gnutls-dev \
    && docker-php-ext-install pdo pdo_mysql bcmath

# Install Redis extension via PECL
RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

# Copy Composer from the official Composer image
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Fix permissions for the repositories
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html

# Set the environment variable for port
ENV PORT=8001