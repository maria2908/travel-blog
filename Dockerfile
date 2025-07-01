# Use official PHP + Apache base image
FROM php:8.2-apache

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Copy all app files into the container
COPY . /var/www/html/

# Enable Apache rewrite (optional)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html/

EXPOSE 80

RUN chmod +x start.sh

CMD ["./start.sh"]