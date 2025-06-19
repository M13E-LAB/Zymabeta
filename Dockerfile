FROM php:8.2-cli

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

# Set working directory
WORKDIR /app

# Copy ALL application files first (including artisan)
COPY . .

# Install PHP dependencies (now artisan exists)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Set permissions and create database
RUN mkdir -p database storage/logs storage/framework/cache storage/framework/sessions storage/framework/views \
    && touch database/database.sqlite \
    && chmod -R 777 storage \
    && chmod -R 777 bootstrap/cache \
    && chmod -R 777 database

# Create detailed startup script with debugging
RUN echo '#!/bin/bash\n\
set -e\n\
cd /app\n\
echo "🚀 Starting ZYMA Laravel App..."\n\
echo "Current directory: $(pwd)"\n\
echo "Port: $PORT"\n\
\n\
# Create .env file if it doesnt exist\n\
if [ ! -f .env ]; then\n\
  echo "📝 Creating .env file..."\n\
  cat > .env << EOF\n\
APP_NAME=ZYMA\n\
APP_ENV=production\n\
APP_KEY=base64:mcSE07/xaGmT9Beq4xuzbFsd3SUJJTje8kFpZnUeW3k=\n\
APP_DEBUG=true\n\
APP_URL=https://zymabeta-production-6713.up.railway.app\n\
\n\
DB_CONNECTION=sqlite\n\
DB_DATABASE=/app/database/database.sqlite\n\
\n\
CACHE_DRIVER=file\n\
SESSION_DRIVER=file\n\
QUEUE_CONNECTION=sync\n\
MAIL_MAILER=log\n\
LOG_CHANNEL=stderr\n\
EOF\n\
  echo "✅ .env file created"\n\
else\n\
  echo "✅ .env file already exists"\n\
fi\n\
\n\
# Setup database\n\
echo "📁 Setting up database..."\n\
touch database/database.sqlite\n\
chmod 777 database/database.sqlite\n\
echo "Database file: $(ls -la database/database.sqlite)"\n\
\n\
# Test Laravel installation\n\
echo "🧪 Testing Laravel..."\n\
php artisan --version || echo "Laravel command failed"\n\
\n\
# Run migrations (ignore if table exists)\n\
echo "🗄️ Running migrations..."\n\
php artisan migrate --force --no-interaction || echo "Migration failed but continuing"\n\
\n\
# Test basic Laravel functionality\n\
echo "🔍 Testing basic Laravel..."\n\
php -r "echo \"PHP version: \" . PHP_VERSION . \"\\n\";"\n\
php -r "require \"vendor/autoload.php\"; echo \"Autoload OK\\n\";"\n\
\n\
# Test Laravel bootstrap\n\
echo "🔧 Testing Laravel bootstrap..."\n\
cd public\n\
php -r "try { require \"index.php\"; echo \"Laravel bootstrap: OK\\n\"; } catch (Exception \\$e) { echo \"Laravel bootstrap ERROR: \" . \\$e->getMessage() . \"\\n\"; }"\n\
cd ..\n\
\n\
echo "✅ Starting server on port $PORT"\n\
echo "📊 Server will be available at: http://0.0.0.0:$PORT"\n\
echo "🧪 Test simple PHP: http://0.0.0.0:$PORT/test.php"\n\
\n\
# Start server with error reporting\n\
exec php -d display_errors=1 -d error_reporting=E_ALL -S 0.0.0.0:$PORT -t public/\n' > /start.sh \
    && chmod +x /start.sh

# Expose port
EXPOSE $PORT

# Start application
CMD ["/start.sh"] 