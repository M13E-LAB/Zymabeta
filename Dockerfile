FROM php:8.2-cli

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
    && docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files first (for better caching)
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy application files
COPY . .

# Set permissions and create database
RUN mkdir -p database storage/logs storage/framework/cache storage/framework/sessions storage/framework/views \
    && touch database/database.sqlite \
    && chmod -R 755 storage \
    && chmod -R 755 bootstrap/cache

# Create simple startup script
RUN echo '#!/bin/bash\n\
cd /app\n\
echo "🚀 Starting ZYMA Laravel App..."\n\
\n\
# Setup database\n\
touch database/database.sqlite\n\
\n\
# Clear caches\n\
php artisan config:clear 2>/dev/null || true\n\
php artisan cache:clear 2>/dev/null || true\n\
\n\
# Generate key if needed\n\
php artisan key:generate --force 2>/dev/null || true\n\
\n\
# Run migrations\n\
php artisan migrate --force --no-interaction 2>/dev/null || true\n\
\n\
echo "✅ Starting server on port $PORT"\n\
\n\
# Start server\n\
exec php -S 0.0.0.0:$PORT -t public/\n' > /start.sh \
    && chmod +x /start.sh

# Expose port
EXPOSE $PORT

# Start application
CMD ["/start.sh"] 