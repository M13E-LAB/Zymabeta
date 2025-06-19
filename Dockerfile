FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Create database directory
RUN mkdir -p /var/www/html/database && touch /var/www/html/database/database.sqlite

# Apache configuration
RUN a2enmod rewrite
COPY .htaccess /var/www/html/.htaccess

# Create startup script
RUN echo '#!/bin/bash' > /start.sh && \
    echo 'cd /var/www/html' >> /start.sh && \
    echo 'cp .env.example .env' >> /start.sh && \
    echo 'php artisan key:generate --force' >> /start.sh && \
    echo 'php artisan migrate --force' >> /start.sh && \
    echo 'php artisan config:cache' >> /start.sh && \
    echo 'apache2-foreground' >> /start.sh && \
    chmod +x /start.sh

# Expose port
EXPOSE 80

# Start application
CMD ["/start.sh"] 