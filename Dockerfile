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
# Enable error reporting for debugging\n\
set -e\n\
\n\
echo "=== Starting Laravel setup ===" >&2\n\
\n\
# Change to the correct directory\n\
cd /var/www\n\
\n\
# Create .env file if it does not exist\n\
if [ ! -f .env ]; then\n\
    echo "Creating .env file..." >&2\n\
    if [ -f .env.example ]; then\n\
        cp .env.example .env\n\
        echo ".env created from .env.example" >&2\n\
    else\n\
        echo "Creating default .env file..." >&2\n\
        cat > .env << EOF\n\
APP_NAME=ZYMA\n\
APP_ENV=production\n\
APP_KEY=\n\
APP_DEBUG=false\n\
APP_URL=https://zymabeta.onrender.com\n\
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
BROADCAST_CONNECTION=log\n\
FILESYSTEM_DISK=local\n\
EOF\n\
    fi\n\
else\n\
    echo ".env file already exists" >&2\n\
fi\n\
\n\
# Ensure the .env file is readable\n\
chmod 644 .env\n\
chown www-data:www-data .env\n\
\n\
# Show current .env content for debugging\n\
echo "=== Current .env file ===" >&2\n\
cat .env >&2\n\
echo "=========================" >&2\n\
\n\
# Generate APP_KEY if empty or missing\n\
echo "Checking APP_KEY..." >&2\n\
if ! grep -q "APP_KEY=base64:" .env; then\n\
    echo "Generating new APP_KEY..." >&2\n\
    php artisan key:generate --force --no-interaction\n\
    echo "APP_KEY generated successfully" >&2\n\
else\n\
    echo "APP_KEY already exists" >&2\n\
fi\n\
\n\
# Clear any existing cache before proceeding\n\
echo "Clearing Laravel cache..." >&2\n\
php artisan cache:clear --no-interaction 2>/dev/null || echo "Cache clear failed (normal on first run)" >&2\n\
php artisan config:clear --no-interaction 2>/dev/null || echo "Config clear failed (normal on first run)" >&2\n\
php artisan route:clear --no-interaction 2>/dev/null || echo "Route clear failed (normal on first run)" >&2\n\
php artisan view:clear --no-interaction 2>/dev/null || echo "View clear failed (normal on first run)" >&2\n\
\n\
# Initialize SQLite database\n\
echo "=== Initializing SQLite database ===" >&2\n\
\n\
# Ensure database directory exists\n\
mkdir -p /var/www/database\n\
\n\
# Create or reset database file\n\
touch /var/www/database/database.sqlite\n\
chmod 664 /var/www/database/database.sqlite\n\
chown www-data:www-data /var/www/database/database.sqlite\n\
\n\
# Initialize database with fresh migrations\n\
echo "Running fresh migrations..." >&2\n\
php artisan migrate:fresh --force --no-interaction --seed 2>&1 || {\n\
    echo "Fresh migration failed, trying regular migrate..." >&2\n\
    php artisan migrate --force --no-interaction 2>&1 || {\n\
        echo "Regular migration also failed, trying to continue..." >&2\n\
    }\n\
}\n\
\n\
# Cache Laravel configurations after database is ready\n\
echo "Caching Laravel configurations..." >&2\n\
php artisan config:cache --no-interaction\n\
php artisan route:cache --no-interaction\n\
php artisan view:cache --no-interaction\n\
\n\
# Create storage link\n\
echo "Creating storage link..." >&2\n\
php artisan storage:link --no-interaction 2>/dev/null || echo "Storage link already exists" >&2\n\
\n\
echo "=== Laravel setup completed successfully ===" >&2\n\
\n\
# Start supervisor\n\
echo "Starting supervisor..." >&2\n\
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