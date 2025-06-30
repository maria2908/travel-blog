# Use PHP with Apache
FROM php:8.2-apache

# Copy your site files to the web server root
COPY . /var/www/html/

# Enable Apache rewrite module (optional, for clean URLs)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html/

# Expose port 80 for web traffic
EXPOSE 80
