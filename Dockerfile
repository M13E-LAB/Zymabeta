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
    && touch /var/www/database/database.sqlite \
    && chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache \
    && chmod 664 /var/www/database/database.sqlite

# Create startup script
RUN echo '#!/bin/bash\n\
# Enable verbose error reporting\n\
set -ex\n\
\n\
echo "=== Starting Laravel setup with full debugging ===" >&2\n\
\n\
# Change to the correct directory\n\
cd /var/www\n\
\n\
# Show environment\n\
echo "=== Environment Variables ===" >&2\n\
env | grep -E "(APP_|DB_|LOG_)" >&2 || echo "No APP/DB/LOG vars found" >&2\n\
\n\
# Create .env file if it does not exist\n\
if [ ! -f .env ]; then\n\
    echo "Creating default .env file..." >&2\n\
    cat > .env << EOF\n\
APP_NAME=ZYMA\n\
APP_ENV=production\n\
APP_KEY=\n\
APP_DEBUG=true\n\
APP_URL=https://zymabeta.onrender.com\n\
\n\
LOG_CHANNEL=stderr\n\
LOG_LEVEL=debug\n\
LOG_STACK=stderr\n\
\n\
DB_CONNECTION=sqlite\n\
DB_DATABASE=/var/www/database/database.sqlite\n\
\n\
CACHE_STORE=file\n\
SESSION_DRIVER=file\n\
QUEUE_CONNECTION=sync\n\
\n\
BROADCAST_CONNECTION=log\n\
FILESYSTEM_DISK=local\n\
EOF\n\
else\n\
    echo ".env file already exists" >&2\n\
fi\n\
\n\
# Show .env content\n\
echo "=== Current .env file ===" >&2\n\
cat .env >&2\n\
echo "=========================" >&2\n\
\n\
# Generate APP_KEY if empty\n\
if ! grep -q "APP_KEY=base64:" .env; then\n\
    echo "Generating APP_KEY..." >&2\n\
    php artisan key:generate --force --no-interaction\n\
    echo "APP_KEY generated" >&2\n\
fi\n\
\n\
# Test PHP and Laravel\n\
echo "=== Testing PHP and Laravel ===" >&2\n\
php --version >&2\n\
php artisan --version >&2 || echo "Laravel not responding!" >&2\n\
\n\
# Clear cache\n\
echo "Clearing cache..." >&2\n\
php artisan cache:clear --no-interaction 2>&1 || echo "Cache clear failed" >&2\n\
php artisan config:clear --no-interaction 2>&1 || echo "Config clear failed" >&2\n\
\n\
# Initialize database\n\
echo "=== Database setup ===" >&2\n\
mkdir -p /var/www/database\n\
touch /var/www/database/database.sqlite\n\
chmod 664 /var/www/database/database.sqlite\n\
chown www-data:www-data /var/www/database/database.sqlite\n\
\n\
# Test database connection\n\
echo "Testing database connection..." >&2\n\
php artisan db:show >&2 || echo "DB connection test failed" >&2\n\
\n\
# Run migrations\n\
echo "Running migrations..." >&2\n\
php artisan migrate:fresh --force --no-interaction 2>&1 || {\n\
    echo "Fresh migration failed, trying regular..." >&2\n\
    php artisan migrate --force --no-interaction 2>&1 || echo "Migration failed completely" >&2\n\
}\n\
\n\
# Test a simple route\n\
echo "=== Testing Laravel routing ===" >&2\n\
php artisan route:list >&2 || echo "Route list failed" >&2\n\
\n\
# Cache configurations\n\
echo "Caching configurations..." >&2\n\
php artisan config:cache --no-interaction >&2\n\
php artisan route:cache --no-interaction >&2\n\
php artisan view:cache --no-interaction >&2\n\
\n\
# Final test\n\
echo "=== Final Laravel test ===" >&2\n\
php artisan tinker --execute="echo \"Laravel is working!\";" >&2 || echo "Laravel test failed!" >&2\n\
\n\
echo "=== Starting web servers ===" >&2\n\
\n\
# Start PHP-FPM in background\n\
echo "Starting PHP-FPM..." >&2\n\
php-fpm -D\n\
\n\
# Start Nginx in background\n\
echo "Starting Nginx..." >&2\n\
nginx -g "daemon off;" &\n\
\n\
# Keep container running and show logs\n\
echo "=== Services started, monitoring logs ===" >&2\n\
tail -f /var/log/nginx/error.log /var/www/storage/logs/laravel.log 2>/dev/null &\n\
\n\
# Wait forever\n\
wait\n\
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

# Ensure the startup script is executable and test it
RUN chmod +x /var/www/startup.sh && ls -la /var/www/startup.sh

CMD ["bash", "/var/www/startup.sh"] 