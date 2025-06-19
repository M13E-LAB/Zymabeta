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
    libpq-dev \
    nginx

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

# Create startup script for Railway
RUN echo '#!/bin/bash\n\
set -e\n\
cd /var/www\n\
\n\
echo "🚀 Starting ZYMA on Railway..."\n\
\n\
# Create .env if not exists\n\
if [ ! -f .env ]; then\n\
    echo "Creating .env file..."\n\
    cat > .env << EOF\n\
APP_NAME=ZYMA\n\
APP_ENV=production\n\
APP_KEY=\n\
APP_DEBUG=false\n\
APP_URL=${APP_URL:-https://zymabeta.up.railway.app}\n\
\n\
LOG_CHANNEL=stderr\n\
LOG_LEVEL=info\n\
\n\
DB_CONNECTION=sqlite\n\
DB_DATABASE=/var/www/database/database.sqlite\n\
\n\
CACHE_STORE=file\n\
SESSION_DRIVER=file\n\
QUEUE_CONNECTION=sync\n\
\n\
MAIL_MAILER=log\n\
EOF\n\
fi\n\
\n\
# Generate APP_KEY if needed\n\
if ! grep -q "APP_KEY=base64:" .env; then\n\
    php artisan key:generate --force\n\
fi\n\
\n\
# Clear caches\n\
php artisan cache:clear\n\
php artisan config:clear\n\
\n\
# Setup database\n\
php artisan migrate:fresh --force --seed || php artisan migrate --force\n\
\n\
# Cache for production\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
echo "✅ ZYMA ready on Railway!"\n\
\n\
# Start server\n\
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}\n\
' > /var/www/railway-start.sh && chmod +x /var/www/railway-start.sh

EXPOSE 8000

CMD ["/var/www/railway-start.sh"] 