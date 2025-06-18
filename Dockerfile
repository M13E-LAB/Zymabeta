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
    nginx \
    supervisor \
    sqlite3 \
    libsqlite3-dev \
    pkg-config

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

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
    && touch /var/www/database/database.sqlite \
    && chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache \
    && chmod 664 /var/www/database/database.sqlite

# Create startup script
RUN echo '#!/bin/bash\n\
# Setup Laravel environment\n\
if [ ! -f .env ]; then\n\
    cp .env.example .env\n\
fi\n\
\n\
# Set production environment\n\
sed -i "s/APP_ENV=local/APP_ENV=production/" .env\n\
sed -i "s/APP_DEBUG=true/APP_DEBUG=false/" .env\n\
sed -i "s/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/" .env\n\
sed -i "s/DB_DATABASE=laravel/#DB_DATABASE=/" .env\n\
\n\
# Generate key if not exists\n\
if ! grep -q "APP_KEY=" .env || [ "$(grep "APP_KEY=" .env | cut -d= -f2)" = "" ]; then\n\
    php artisan key:generate --no-interaction\n\
fi\n\
\n\
# Run migrations\n\
php artisan migrate --force\n\
\n\
# Cache configuration\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
# Create storage link\n\
php artisan storage:link\n\
\n\
# Start supervisor\n\
/usr/bin/supervisord\n\
' > /var/www/startup.sh && chmod +x /var/www/startup.sh

# Create nginx config
RUN echo 'server {\n\
    listen 80;\n\
    index index.php index.html;\n\
    root /var/www/public;\n\
    location / {\n\
        try_files $uri $uri/ /index.php?$query_string;\n\
    }\n\
    location ~ \.php$ {\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_index index.php;\n\
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;\n\
        include fastcgi_params;\n\
    }\n\
}' > /etc/nginx/sites-available/default

# Create supervisor config
RUN echo '[supervisord]\n\
nodaemon=true\n\
[program:nginx]\n\
command=nginx -g "daemon off;"\n\
autostart=true\n\
autorestart=true\n\
[program:php-fpm]\n\
command=php-fpm\n\
autostart=true\n\
autorestart=true' > /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/var/www/startup.sh"] 