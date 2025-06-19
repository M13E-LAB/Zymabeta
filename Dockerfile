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
    && docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache modules
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy ALL application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage \
    && chmod -R 755 bootstrap/cache \
    && mkdir -p database \
    && touch database/database.sqlite \
    && chmod -R 777 database

# Create startup script with dynamic port
RUN echo '#!/bin/bash\n\
cd /var/www/html\n\
echo "🚀 ZYMA Starting with Apache on port $PORT..."\n\
\n\
# Configure Apache for dynamic port\n\
echo "Listen $PORT" > /etc/apache2/ports.conf\n\
echo "<VirtualHost *:$PORT>" > /etc/apache2/sites-available/000-default.conf\n\
echo "    DocumentRoot /var/www/html/public" >> /etc/apache2/sites-available/000-default.conf\n\
echo "    <Directory /var/www/html/public>" >> /etc/apache2/sites-available/000-default.conf\n\
echo "        AllowOverride All" >> /etc/apache2/sites-available/000-default.conf\n\
echo "        Require all granted" >> /etc/apache2/sites-available/000-default.conf\n\
echo "    </Directory>" >> /etc/apache2/sites-available/000-default.conf\n\
echo "    ErrorLog /var/log/apache2/error.log" >> /etc/apache2/sites-available/000-default.conf\n\
echo "    CustomLog /var/log/apache2/access.log combined" >> /etc/apache2/sites-available/000-default.conf\n\
echo "</VirtualHost>" >> /etc/apache2/sites-available/000-default.conf\n\
\n\
# Create .env for production\n\
cat > .env << EOF\n\
APP_NAME=ZYMA\n\
APP_ENV=production\n\
APP_KEY=base64:mcSE07/xaGmT9Beq4xuzbFsd3SUJJTje8kFpZnUeW3k=\n\
APP_DEBUG=false\n\
APP_URL=https://zymabeta-production-6713.up.railway.app\n\
\n\
DB_CONNECTION=sqlite\n\
DB_DATABASE=/var/www/html/database/database.sqlite\n\
\n\
CACHE_DRIVER=file\n\
SESSION_DRIVER=file\n\
QUEUE_CONNECTION=sync\n\
MAIL_MAILER=log\n\
LOG_CHANNEL=single\n\
LOG_LEVEL=error\n\
EOF\n\
\n\
# Setup database and Laravel\n\
touch database/database.sqlite\n\
chmod 777 database/database.sqlite\n\
php artisan migrate --force --no-interaction >/dev/null 2>&1 || true\n\
php artisan config:clear >/dev/null 2>&1 || true\n\
php artisan cache:clear >/dev/null 2>&1 || true\n\
\n\
echo "✅ ZYMA Ready with Apache on port $PORT"\n\
\n\
# Start Apache\n\
exec apache2-foreground\n' > /start.sh \
    && chmod +x /start.sh

# Expose port (dynamic)
EXPOSE $PORT

# Start application
CMD ["/start.sh"] 