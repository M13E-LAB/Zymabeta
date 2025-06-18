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
# Setup Laravel environment\n\
if [ ! -f .env ]; then\n\
    if [ -f .env.example ]; then\n\
        cp .env.example .env\n\
    else\n\
        echo "APP_NAME=ZYMA" > .env\n\
        echo "APP_ENV=production" >> .env\n\
        echo "APP_KEY=" >> .env\n\
        echo "APP_DEBUG=false" >> .env\n\
        echo "APP_URL=${APP_URL:-http://localhost}" >> .env\n\
        echo "" >> .env\n\
        echo "LOG_CHANNEL=stack" >> .env\n\
        echo "LOG_LEVEL=error" >> .env\n\
        echo "" >> .env\n\
        echo "SESSION_DRIVER=database" >> .env\n\
        echo "CACHE_STORE=database" >> .env\n\
        echo "QUEUE_CONNECTION=database" >> .env\n\
    fi\n\
fi\n\
\n\
# Set production environment variables\n\
sed -i "s/APP_ENV=local/APP_ENV=production/" .env\n\
sed -i "s/APP_DEBUG=true/APP_DEBUG=false/" .env\n\
\n\
# Configure database connection for Render\n\
if [ ! -z "$DATABASE_URL" ]; then\n\
    # Parse DATABASE_URL for Render PostgreSQL\n\
    echo "DATABASE_URL found, configuring PostgreSQL..." >&2\n\
    sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=pgsql/" .env\n\
    # Remove any existing DATABASE_URL line\n\
    sed -i "/^DATABASE_URL=/d" .env\n\
    echo "DATABASE_URL=$DATABASE_URL" >> .env\n\
    # Remove SQLite specific configs\n\
    sed -i "/^DB_DATABASE=.*\\.sqlite/d" .env\n\
elif [ ! -z "$PGHOST" ]; then\n\
    # Alternative PostgreSQL environment variables\n\
    echo "PostgreSQL environment variables found..." >&2\n\
    sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=pgsql/" .env\n\
    sed -i "s/DB_HOST=.*/DB_HOST=$PGHOST/" .env\n\
    sed -i "s/DB_PORT=.*/DB_PORT=${PGPORT:-5432}/" .env\n\
    sed -i "s/DB_DATABASE=.*/DB_DATABASE=$PGDATABASE/" .env\n\
    sed -i "s/DB_USERNAME=.*/DB_USERNAME=$PGUSER/" .env\n\
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$PGPASSWORD/" .env\n\
    # Remove SQLite specific configs\n\
    sed -i "/^DB_DATABASE=.*\\.sqlite/d" .env\n\
else\n\
    # Fallback to SQLite\n\
    echo "No PostgreSQL config found, using SQLite..." >&2\n\
    sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=sqlite/" .env\n\
    # Remove PostgreSQL specific configs\n\
    sed -i "/^DB_HOST=/d" .env\n\
    sed -i "/^DB_PORT=/d" .env\n\
    sed -i "/^DB_USERNAME=/d" .env\n\
    sed -i "/^DB_PASSWORD=/d" .env\n\
    sed -i "/^DATABASE_URL=/d" .env\n\
    # Set SQLite database path\n\
    if ! grep -q "DB_DATABASE=" .env; then\n\
        echo "DB_DATABASE=/var/www/database/database.sqlite" >> .env\n\
    else\n\
        sed -i "s|DB_DATABASE=.*|DB_DATABASE=/var/www/database/database.sqlite|" .env\n\
    fi\n\
fi\n\
\n\
# Set APP_URL if provided\n\
if [ ! -z "$APP_URL" ]; then\n\
    sed -i "s|APP_URL=.*|APP_URL=$APP_URL|" .env\n\
fi\n\
\n\
# Generate key if not exists or empty\n\
if ! grep -q "APP_KEY=" .env || [ "$(grep "APP_KEY=" .env | cut -d= -f2)" = "" ]; then\n\
    php artisan key:generate --no-interaction --force\n\
fi\n\
\n\
# Clear Laravel cache after env is properly configured\n\
php artisan config:clear 2>/dev/null || true\n\
php artisan cache:clear 2>/dev/null || true\n\
\n\
# Debug: Show current database configuration\n\
echo "Current database configuration:" >&2\n\
cat .env | grep -E "(DB_|DATABASE_|APP_KEY)" >&2\n\
\n\
# Initialize database if using SQLite\n\
if grep -q "DB_CONNECTION=sqlite" .env; then\n\
    echo "Initializing SQLite database..." >&2\n\
    # Ensure database file exists and has proper permissions\n\
    touch /var/www/database/database.sqlite\n\
    chmod 664 /var/www/database/database.sqlite\n\
    chown www-data:www-data /var/www/database/database.sqlite\n\
    # Run fresh migrations for SQLite\n\
    php artisan migrate:fresh --force --seed 2>/dev/null || php artisan migrate --force\n\
else\n\
    # Test database connection for PostgreSQL\n\
    echo "Testing PostgreSQL connection..." >&2\n\
    if php artisan migrate:status --no-interaction 2>/dev/null; then\n\
        echo "Database connection successful, running migrations..." >&2\n\
        php artisan migrate --force\n\
    else\n\
        echo "Database connection failed, check configuration" >&2\n\
    fi\n\
fi\n\
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