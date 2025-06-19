FROM php:8.2-fpm

# Install system dependencies
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
    pkg-config \
    libpq-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_sqlite pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . /var/www

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Create directories and set permissions
RUN mkdir -p /var/www/storage/logs \
    && mkdir -p /var/www/bootstrap/cache \
    && mkdir -p /var/www/database \
    && touch /var/www/database/database.sqlite \
    && chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache \
    && chmod 664 /var/www/database/database.sqlite

# Create simple startup script
RUN echo '#!/bin/bash' > /var/www/start.sh && \
    echo 'set -e' >> /var/www/start.sh && \
    echo 'cd /var/www' >> /var/www/start.sh && \
    echo 'echo "🚀 Starting ZYMA on Railway..."' >> /var/www/start.sh && \
    echo 'php artisan key:generate --force --no-interaction' >> /var/www/start.sh && \
    echo 'php artisan cache:clear --no-interaction' >> /var/www/start.sh && \
    echo 'php artisan config:clear --no-interaction' >> /var/www/start.sh && \
    echo 'php artisan migrate --force --no-interaction || true' >> /var/www/start.sh && \
    echo 'echo "✅ ZYMA ready! Starting server on port ${PORT:-8000}"' >> /var/www/start.sh && \
    echo 'exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}' >> /var/www/start.sh

RUN chmod +x /var/www/start.sh

EXPOSE ${PORT:-8000}

CMD ["/var/www/start.sh"] 