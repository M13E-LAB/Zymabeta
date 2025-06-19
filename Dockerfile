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

# Configure Apache for Laravel
RUN a2enmod rewrite && \
    echo '<VirtualHost *:80>' > /etc/apache2/sites-available/000-default.conf && \
    echo '    DocumentRoot /var/www/html/public' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    <Directory /var/www/html/public>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        AllowOverride All' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        Require all granted' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    </Directory>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    ErrorLog ${APACHE_LOG_DIR}/error.log' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    CustomLog ${APACHE_LOG_DIR}/access.log combined' >> /etc/apache2/sites-available/000-default.conf && \
    echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf

# Create startup script
RUN echo '#!/bin/bash' > /start.sh && \
    echo 'cd /var/www/html' >> /start.sh && \
    echo 'echo "🚀 Creating complete .env file..."' >> /start.sh && \
    echo 'cp .env.example .env 2>/dev/null || echo "No .env.example found, creating new .env"' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Core Laravel variables' >> /start.sh && \
    echo 'echo "APP_NAME=ZYMA" >> .env' >> /start.sh && \
    echo 'echo "APP_ENV=production" >> .env' >> /start.sh && \
    echo 'echo "APP_DEBUG=false" >> .env' >> /start.sh && \
    echo 'echo "APP_URL=https://${RAILWAY_PUBLIC_DOMAIN:-localhost}" >> .env' >> /start.sh && \
    echo 'echo "APP_KEY=${APP_KEY}" >> .env' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Database' >> /start.sh && \
    echo 'echo "DB_CONNECTION=sqlite" >> .env' >> /start.sh && \
    echo 'echo "DB_DATABASE=/var/www/html/database/database.sqlite" >> .env' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Basic config' >> /start.sh && \
    echo 'echo "CACHE_DRIVER=file" >> .env' >> /start.sh && \
    echo 'echo "SESSION_DRIVER=file" >> .env' >> /start.sh && \
    echo 'echo "QUEUE_CONNECTION=sync" >> .env' >> /start.sh && \
    echo 'echo "MAIL_MAILER=log" >> .env' >> /start.sh && \
    echo 'echo "LOG_CHANNEL=stack" >> .env' >> /start.sh && \
    echo '' >> /start.sh && \
    echo 'echo "✅ .env file created"' >> /start.sh && \
    echo 'php artisan key:generate --force 2>/dev/null || echo "Key already set"' >> /start.sh && \
    echo 'php artisan migrate --force 2>/dev/null || echo "Migration skipped"' >> /start.sh && \
    echo 'php artisan config:cache' >> /start.sh && \
    echo 'echo "🚀 Starting Apache server..."' >> /start.sh && \
    echo 'apache2-foreground' >> /start.sh && \
    chmod +x /start.sh

# Expose port
EXPOSE 80

# Start application
CMD ["/start.sh"] 