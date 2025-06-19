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
    echo 'echo "🚀 Creating complete .env file..."' >> /start.sh && \
    echo 'cp .env.example .env' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Core Laravel variables' >> /start.sh && \
    echo 'echo "APP_NAME=ZYMA" >> .env' >> /start.sh && \
    echo 'echo "APP_ENV=production" >> .env' >> /start.sh && \
    echo 'echo "APP_DEBUG=false" >> .env' >> /start.sh && \
    echo 'echo "APP_URL=https://${RAILWAY_PUBLIC_DOMAIN:-localhost}" >> .env' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Database' >> /start.sh && \
    echo 'echo "DB_CONNECTION=sqlite" >> .env' >> /start.sh && \
    echo 'echo "DB_DATABASE=/var/www/html/database/database.sqlite" >> .env' >> /start.sh && \
    echo 'echo "DATABASE_URL=" >> .env' >> /start.sh && \
    echo 'echo "DB_URL=" >> .env' >> /start.sh && \
    echo 'echo "MYSQL_ATTR_SSL_CA=" >> .env' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Mail Configuration' >> /start.sh && \
    echo 'echo "MAIL_MAILER=log" >> .env' >> /start.sh && \
    echo 'echo "MAIL_URL=" >> .env' >> /start.sh && \
    echo 'echo "MAIL_USERNAME=" >> .env' >> /start.sh && \
    echo 'echo "MAIL_PASSWORD=" >> .env' >> /start.sh && \
    echo 'echo "MAIL_LOG_CHANNEL=" >> .env' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Logging' >> /start.sh && \
    echo 'echo "LOG_CHANNEL=stack" >> .env' >> /start.sh && \
    echo 'echo "LOG_STDERR_FORMATTER=" >> .env' >> /start.sh && \
    echo 'echo "PAPERTRAIL_URL=" >> .env' >> /start.sh && \
    echo 'echo "PAPERTRAIL_PORT=" >> .env' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Session & Cache' >> /start.sh && \
    echo 'echo "SESSION_DRIVER=file" >> .env' >> /start.sh && \
    echo 'echo "SESSION_CONNECTION=" >> .env' >> /start.sh && \
    echo 'echo "SESSION_STORE=" >> .env' >> /start.sh && \
    echo 'echo "SESSION_DOMAIN=" >> .env' >> /start.sh && \
    echo 'echo "SESSION_SECURE_COOKIE=" >> .env' >> /start.sh && \
    echo 'echo "CACHE_DRIVER=file" >> .env' >> /start.sh && \
    echo 'echo "DB_CACHE_CONNECTION=" >> .env' >> /start.sh && \
    echo 'echo "DB_CACHE_LOCK_CONNECTION=" >> .env' >> /start.sh && \
    echo 'echo "DB_CACHE_LOCK_TABLE=" >> .env' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Queue' >> /start.sh && \
    echo 'echo "QUEUE_CONNECTION=sync" >> .env' >> /start.sh && \
    echo 'echo "DB_QUEUE_CONNECTION=" >> .env' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# AWS Services' >> /start.sh && \
    echo 'echo "AWS_ACCESS_KEY_ID=" >> .env' >> /start.sh && \
    echo 'echo "AWS_SECRET_ACCESS_KEY=" >> .env' >> /start.sh && \
    echo 'echo "AWS_DEFAULT_REGION=us-east-1" >> .env' >> /start.sh && \
    echo 'echo "AWS_BUCKET=" >> .env' >> /start.sh && \
    echo 'echo "SQS_SUFFIX=" >> .env' >> /start.sh && \
    echo 'echo "DYNAMODB_ENDPOINT=" >> .env' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# External Services' >> /start.sh && \
    echo 'echo "POSTMARK_TOKEN=" >> .env' >> /start.sh && \
    echo 'echo "POSTMARK_MESSAGE_STREAM_ID=" >> .env' >> /start.sh && \
    echo 'echo "RESEND_KEY=" >> .env' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Slack' >> /start.sh && \
    echo 'echo "SLACK_BOT_USER_OAUTH_TOKEN=" >> .env' >> /start.sh && \
    echo 'echo "SLACK_BOT_USER_DEFAULT_CHANNEL=" >> .env' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Memcached' >> /start.sh && \
    echo 'echo "MEMCACHED_PERSISTENT_ID=" >> .env' >> /start.sh && \
    echo 'echo "MEMCACHED_USERNAME=" >> .env' >> /start.sh && \
    echo 'echo "MEMCACHED_PASSWORD=" >> .env' >> /start.sh && \
    echo '' >> /start.sh && \
    echo 'echo "✅ .env file created with ALL variables"' >> /start.sh && \
    echo 'php artisan key:generate --force' >> /start.sh && \
    echo 'php artisan migrate --force' >> /start.sh && \
    echo 'php artisan config:cache' >> /start.sh && \
    echo 'echo "🚀 Starting Apache server..."' >> /start.sh && \
    echo 'apache2-foreground' >> /start.sh && \
    chmod +x /start.sh

# Expose port
EXPOSE 80

# Start application
CMD ["/start.sh"] 