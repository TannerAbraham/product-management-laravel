# Use official PHP 8.2 image with Apache
FROM php:8.2-apache

# Install required system packages (now includes libsqlite3-dev)
RUN apt-get update && apt-get install -y \
    zip unzip git libpng-dev libonig-dev libxml2-dev libsqlite3-dev pkg-config \
    && docker-php-ext-install pdo pdo_sqlite

# Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# Set Apache DocumentRoot to Laravel's /public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/sites-available/default-ssl.conf \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy project files
COPY . /var/www/html

WORKDIR /var/www/html

# Install Composer and PHP dependencies
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Copy .env.example → .env if missing
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Generate Laravel application key
RUN php artisan key:generate

# Fix permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]

