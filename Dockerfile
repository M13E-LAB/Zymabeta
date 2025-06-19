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

# Create comprehensive startup script for Railway
RUN echo '#!/bin/bash' > /var/www/railway-start.sh
RUN echo 'set -e' >> /var/www/railway-start.sh
RUN echo 'cd /var/www' >> /var/www/railway-start.sh
RUN echo '' >> /var/www/railway-start.sh
RUN echo 'echo "🚀 Starting ZYMA on Railway..."' >> /var/www/railway-start.sh
RUN echo '' >> /var/www/railway-start.sh
RUN echo '# Create comprehensive .env file' >> /var/www/railway-start.sh
RUN echo 'if [ ! -f .env ]; then' >> /var/www/railway-start.sh
RUN echo '    echo "📝 Creating complete .env file..."' >> /var/www/railway-start.sh
RUN echo '    cat > .env << EOF' >> /var/www/railway-start.sh
RUN echo 'APP_NAME=ZYMA' >> /var/www/railway-start.sh
RUN echo 'APP_ENV=production' >> /var/www/railway-start.sh
RUN echo 'APP_KEY=' >> /var/www/railway-start.sh
RUN echo 'APP_DEBUG=false' >> /var/www/railway-start.sh
RUN echo 'APP_URL=${RAILWAY_PUBLIC_DOMAIN:-https://zymabeta-production-b78c.up.railway.app}' >> /var/www/railway-start.sh
RUN echo '' >> /var/www/railway-start.sh
RUN echo 'LOG_CHANNEL=stderr' >> /var/www/railway-start.sh
RUN echo 'LOG_LEVEL=info' >> /var/www/railway-start.sh
RUN echo 'LOG_STDERR_FORMATTER=' >> /var/www/railway-start.sh
RUN echo 'LOG_SLACK_WEBHOOK_URL=' >> /var/www/railway-start.sh
RUN echo '' >> /var/www/railway-start.sh
RUN echo 'DB_CONNECTION=sqlite' >> /var/www/railway-start.sh
RUN echo 'DB_DATABASE=/var/www/database/database.sqlite' >> /var/www/railway-start.sh
RUN echo 'DB_QUEUE_CONNECTION=' >> /var/www/railway-start.sh
RUN echo '' >> /var/www/railway-start.sh
RUN echo 'CACHE_STORE=file' >> /var/www/railway-start.sh
RUN echo 'SESSION_DRIVER=file' >> /var/www/railway-start.sh
RUN echo 'SESSION_CONNECTION=' >> /var/www/railway-start.sh
RUN echo 'SESSION_STORE=' >> /var/www/railway-start.sh
RUN echo 'SESSION_DOMAIN=' >> /var/www/railway-start.sh
RUN echo 'SESSION_SECURE_COOKIE=' >> /var/www/railway-start.sh
RUN echo 'QUEUE_CONNECTION=sync' >> /var/www/railway-start.sh
RUN echo '' >> /var/www/railway-start.sh
RUN echo 'MAIL_MAILER=log' >> /var/www/railway-start.sh
RUN echo 'MAIL_URL=' >> /var/www/railway-start.sh
RUN echo 'MAIL_USERNAME=' >> /var/www/railway-start.sh
RUN echo 'MAIL_PASSWORD=' >> /var/www/railway-start.sh
RUN echo 'POSTMARK_TOKEN=' >> /var/www/railway-start.sh
RUN echo 'POSTMARK_MESSAGE_STREAM_ID=' >> /var/www/railway-start.sh
RUN echo 'RESEND_KEY=' >> /var/www/railway-start.sh
RUN echo '' >> /var/www/railway-start.sh
RUN echo 'REDIS_URL=' >> /var/www/railway-start.sh
RUN echo 'REDIS_USERNAME=' >> /var/www/railway-start.sh
RUN echo 'REDIS_PASSWORD=' >> /var/www/railway-start.sh
RUN echo '' >> /var/www/railway-start.sh
RUN echo 'AWS_ACCESS_KEY_ID=' >> /var/www/railway-start.sh
RUN echo 'AWS_SECRET_ACCESS_KEY=' >> /var/www/railway-start.sh
RUN echo 'SQS_SUFFIX=' >> /var/www/railway-start.sh
RUN echo '' >> /var/www/railway-start.sh
RUN echo 'SLACK_BOT_USER_OAUTH_TOKEN=' >> /var/www/railway-start.sh
RUN echo 'SLACK_BOT_USER_DEFAULT_CHANNEL=' >> /var/www/railway-start.sh
RUN echo '' >> /var/www/railway-start.sh
RUN echo 'PAPERTRAIL_URL=' >> /var/www/railway-start.sh
RUN echo 'PAPERTRAIL_PORT=' >> /var/www/railway-start.sh
RUN echo 'EOF' >> /var/www/railway-start.sh
RUN echo 'fi' >> /var/www/railway-start.sh
RUN echo '' >> /var/www/railway-start.sh
RUN echo '# Generate APP_KEY if needed' >> /var/www/railway-start.sh
RUN echo 'if ! grep -q "APP_KEY=base64:" .env; then' >> /var/www/railway-start.sh
RUN echo '    echo "🔑 Generating APP_KEY..."' >> /var/www/railway-start.sh
RUN echo '    php artisan key:generate --force' >> /var/www/railway-start.sh
RUN echo 'fi' >> /var/www/railway-start.sh
RUN echo '' >> /var/www/railway-start.sh
RUN echo '# Clear caches' >> /var/www/railway-start.sh
RUN echo 'echo "🧹 Clearing caches..."' >> /var/www/railway-start.sh
RUN echo 'php artisan cache:clear' >> /var/www/railway-start.sh
RUN echo 'php artisan config:clear' >> /var/www/railway-start.sh
RUN echo '' >> /var/www/railway-start.sh
RUN echo '# Setup database' >> /var/www/railway-start.sh
RUN echo 'echo "🗄️ Setting up database..."' >> /var/www/railway-start.sh
RUN echo 'php artisan migrate:fresh --force --seed || php artisan migrate --force' >> /var/www/railway-start.sh
RUN echo '' >> /var/www/railway-start.sh
RUN echo '# Cache for production' >> /var/www/railway-start.sh
RUN echo 'echo "⚡ Caching configurations..."' >> /var/www/railway-start.sh
RUN echo 'php artisan config:cache' >> /var/www/railway-start.sh
RUN echo 'php artisan route:cache' >> /var/www/railway-start.sh
RUN echo 'php artisan view:cache' >> /var/www/railway-start.sh
RUN echo '' >> /var/www/railway-start.sh
RUN echo 'echo "✅ ZYMA ready on Railway!"' >> /var/www/railway-start.sh
RUN echo 'echo "🌐 Port: ${PORT:-8000}"' >> /var/www/railway-start.sh
RUN echo '' >> /var/www/railway-start.sh
RUN echo '# Start server on Railway PORT' >> /var/www/railway-start.sh
RUN echo 'exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}' >> /var/www/railway-start.sh

RUN chmod +x /var/www/railway-start.sh

EXPOSE ${PORT:-8000}

CMD ["/var/www/railway-start.sh"] 