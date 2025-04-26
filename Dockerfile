# Use the official PHP Apache image
FROM php:8.1-apache

# Enable Apache mod_rewrite (useful for clean URLs)
RUN a2enmod rewrite

# Copy your PHP code into the container
COPY . /var/www/html/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
