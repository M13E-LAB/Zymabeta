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

# Create .env file with all required variables
RUN echo "APP_NAME=ZYMA" > /var/www/.env && \
    echo "APP_ENV=production" >> /var/www/.env && \
    echo "APP_KEY=" >> /var/www/.env && \
    echo "APP_DEBUG=false" >> /var/www/.env && \
    echo "APP_URL=" >> /var/www/.env && \
    echo "" >> /var/www/.env && \
    echo "LOG_CHANNEL=stderr" >> /var/www/.env && \
    echo "LOG_LEVEL=info" >> /var/www/.env && \
    echo "LOG_STDERR_FORMATTER=" >> /var/www/.env && \
    echo "LOG_SLACK_WEBHOOK_URL=" >> /var/www/.env && \
    echo "" >> /var/www/.env && \
    echo "DB_CONNECTION=sqlite" >> /var/www/.env && \
    echo "DB_DATABASE=/var/www/database/database.sqlite" >> /var/www/.env && \
    echo "DB_QUEUE_CONNECTION=" >> /var/www/.env && \
    echo "DB_URL=" >> /var/www/.env && \
    echo "" >> /var/www/.env && \
    echo "CACHE_STORE=file" >> /var/www/.env && \
    echo "SESSION_DRIVER=file" >> /var/www/.env && \
    echo "SESSION_CONNECTION=" >> /var/www/.env && \
    echo "SESSION_STORE=" >> /var/www/.env && \
    echo "SESSION_DOMAIN=" >> /var/www/.env && \
    echo "SESSION_SECURE_COOKIE=" >> /var/www/.env && \
    echo "QUEUE_CONNECTION=sync" >> /var/www/.env && \
    echo "" >> /var/www/.env && \
    echo "MAIL_MAILER=log" >> /var/www/.env && \
    echo "MAIL_URL=" >> /var/www/.env && \
    echo "MAIL_USERNAME=" >> /var/www/.env && \
    echo "MAIL_PASSWORD=" >> /var/www/.env && \
    echo "MAIL_LOG_CHANNEL=" >> /var/www/.env && \
    echo "POSTMARK_TOKEN=" >> /var/www/.env && \
    echo "POSTMARK_MESSAGE_STREAM_ID=" >> /var/www/.env && \
    echo "RESEND_KEY=" >> /var/www/.env && \
    echo "" >> /var/www/.env && \
    echo "REDIS_URL=" >> /var/www/.env && \
    echo "REDIS_USERNAME=" >> /var/www/.env && \
    echo "REDIS_PASSWORD=" >> /var/www/.env && \
    echo "" >> /var/www/.env && \
    echo "AWS_ACCESS_KEY_ID=" >> /var/www/.env && \
    echo "AWS_SECRET_ACCESS_KEY=" >> /var/www/.env && \
    echo "AWS_DEFAULT_REGION=" >> /var/www/.env && \
    echo "AWS_BUCKET=" >> /var/www/.env && \
    echo "AWS_URL=" >> /var/www/.env && \
    echo "AWS_ENDPOINT=" >> /var/www/.env && \
    echo "SQS_SUFFIX=" >> /var/www/.env && \
    echo "" >> /var/www/.env && \
    echo "SLACK_BOT_USER_OAUTH_TOKEN=" >> /var/www/.env && \
    echo "SLACK_BOT_USER_DEFAULT_CHANNEL=" >> /var/www/.env && \
    echo "" >> /var/www/.env && \
    echo "PAPERTRAIL_URL=" >> /var/www/.env && \
    echo "PAPERTRAIL_PORT=" >> /var/www/.env && \
    echo "" >> /var/www/.env && \
    echo "DATABASE_URL=" >> /var/www/.env && \
    echo "MYSQL_ATTR_SSL_CA=" >> /var/www/.env

# Create startup script
RUN echo '#!/bin/bash' > /var/www/start.sh && \
    echo 'set -e' >> /var/www/start.sh && \
    echo '' >> /var/www/start.sh && \
    echo 'echo "🚀 Starting ZYMA on Railway..."' >> /var/www/start.sh && \
    echo '' >> /var/www/start.sh && \
    echo '# Set APP_URL if Railway domain is available' >> /var/www/start.sh && \
    echo 'if [ ! -z "$RAILWAY_PUBLIC_DOMAIN" ]; then' >> /var/www/start.sh && \
    echo '    sed -i "s|APP_URL=|APP_URL=https://${RAILWAY_PUBLIC_DOMAIN}|g" /var/www/.env' >> /var/www/start.sh && \
    echo 'fi' >> /var/www/start.sh && \
    echo '' >> /var/www/start.sh && \
    echo '# Generate APP_KEY if not set' >> /var/www/start.sh && \
    echo 'if ! grep -q "APP_KEY=base64:" /var/www/.env; then' >> /var/www/start.sh && \
    echo '    echo "🔑 Generating APP_KEY..."' >> /var/www/start.sh && \
    echo '    php artisan key:generate --force' >> /var/www/start.sh && \
    echo 'fi' >> /var/www/start.sh && \
    echo '' >> /var/www/start.sh && \
    echo '# Clear caches' >> /var/www/start.sh && \
    echo 'echo "🧹 Clearing caches..."' >> /var/www/start.sh && \
    echo 'php artisan cache:clear' >> /var/www/start.sh && \
    echo 'php artisan config:clear' >> /var/www/start.sh && \
    echo '' >> /var/www/start.sh && \
    echo '# Setup database' >> /var/www/start.sh && \
    echo 'echo "🗄️ Setting up database..."' >> /var/www/start.sh && \
    echo 'php artisan migrate:fresh --force --seed || php artisan migrate --force' >> /var/www/start.sh && \
    echo '' >> /var/www/start.sh && \
    echo '# Cache for production' >> /var/www/start.sh && \
    echo 'echo "⚡ Caching configurations..."' >> /var/www/start.sh && \
    echo 'php artisan config:cache' >> /var/www/start.sh && \
    echo 'php artisan route:cache' >> /var/www/start.sh && \
    echo 'php artisan view:cache' >> /var/www/start.sh && \
    echo '' >> /var/www/start.sh && \
    echo 'echo "✅ ZYMA ready on Railway!"' >> /var/www/start.sh && \
    echo 'echo "🌐 Port: ${PORT:-8000}"' >> /var/www/start.sh && \
    echo '' >> /var/www/start.sh && \
    echo '# Start server on Railway PORT' >> /var/www/start.sh && \
    echo 'exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}' >> /var/www/start.sh

RUN chmod +x /var/www/start.sh

EXPOSE ${PORT:-8000}

CMD ["/var/www/start.sh"] 