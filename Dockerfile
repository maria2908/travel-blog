# Use official PHP + Apache base image
FROM php:8.2-apache

# Install dependencies (for Composer and PHP extensions)
RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    curl \
    git \
    libzip-dev

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer (copy from official Composer image)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy all app files
COPY . .

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PHP dependencies with Composer
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80
