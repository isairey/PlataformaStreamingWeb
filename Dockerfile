# Use official PHP image with Apache
FROM php:8.2-apache

# Copy all project files into the container's web root
COPY . /var/www/html/

# Enable Apache mod_rewrite (optional, if your app uses .htaccess)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html/

# Expose port 80 (HTTP)
EXPOSE 80

# Start Apache in the foreground (default CMD for this image)
CMD ["apache2-foreground"]
