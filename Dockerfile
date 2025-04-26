# Use the official PHP 8.1 image with Apache
FROM php:8.1-apache

# Install system dependencies for PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Install MySQLi and PDO_MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite (optional, but useful for clean URLs)
RUN a2enmod rewrite

# Copy all project files to the Apache server root
COPY . /var/www/html/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 to Render
EXPOSE 80

# Start Apache server (default behavior)
CMD ["apache2-foreground"]
