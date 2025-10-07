# Use the official PHP 8.2 image with Apache
FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip git libpng-dev libonig-dev libxml2-dev \
    sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Enable Apache mod_rewrite (needed for Laravel routing)
RUN a2enmod rewrite

# Copy the application code into the container
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Copy Composer from the Composer image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies (optimized for production)
RUN composer install --no-dev --optimize-autoloader

# Copy .env.example to .env if it doesn't exist
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Generate Laravel app key
RUN php artisan key:generate

# Give proper permissions to storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80 for HTTP
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]

