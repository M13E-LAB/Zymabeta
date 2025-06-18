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

# Create the main startup script
RUN echo '#!/bin/bash' > /var/www/startup.sh && \
    echo 'set -e' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo '# Force all output to stderr so it appears in Render logs' >> /var/www/startup.sh && \
    echo 'exec > >(tee -a /dev/stderr)' >> /var/www/startup.sh && \
    echo 'exec 2>&1' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo 'echo "🚀 ZYMA Laravel Application Starting..."' >> /var/www/startup.sh && \
    echo 'echo "📅 $(date)"' >> /var/www/startup.sh && \
    echo 'echo "🔧 Running on Render.com"' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo 'cd /var/www' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo '# Show environment info' >> /var/www/startup.sh && \
    echo 'echo "=== ENVIRONMENT INFO ==="' >> /var/www/startup.sh && \
    echo 'echo "PWD: $(pwd)"' >> /var/www/startup.sh && \
    echo 'echo "USER: $(whoami)"' >> /var/www/startup.sh && \
    echo 'echo "PORT: ${PORT:-10000}"' >> /var/www/startup.sh && \
    echo 'env | grep -E "(APP_|DB_|LOG_|PORT)" || echo "No relevant env vars found"' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo '# Create .env file' >> /var/www/startup.sh && \
    echo 'echo "📝 Setting up .env file..."' >> /var/www/startup.sh && \
    echo 'if [ ! -f .env ]; then' >> /var/www/startup.sh && \
    echo '    cat > .env << EOF' >> /var/www/startup.sh && \
    echo 'APP_NAME=ZYMA' >> /var/www/startup.sh && \
    echo 'APP_ENV=production' >> /var/www/startup.sh && \
    echo 'APP_KEY=' >> /var/www/startup.sh && \
    echo 'APP_DEBUG=false' >> /var/www/startup.sh && \
    echo 'APP_TIMEZONE=UTC' >> /var/www/startup.sh && \
    echo 'APP_URL=https://zymabeta.onrender.com' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo 'LOG_CHANNEL=stderr' >> /var/www/startup.sh && \
    echo 'LOG_DEPRECATIONS_CHANNEL=null' >> /var/www/startup.sh && \
    echo 'LOG_LEVEL=info' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo 'DB_CONNECTION=sqlite' >> /var/www/startup.sh && \
    echo 'DB_DATABASE=/var/www/database/database.sqlite' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo 'BROADCAST_CONNECTION=log' >> /var/www/startup.sh && \
    echo 'FILESYSTEM_DISK=local' >> /var/www/startup.sh && \
    echo 'QUEUE_CONNECTION=sync' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo 'CACHE_STORE=file' >> /var/www/startup.sh && \
    echo 'CACHE_PREFIX=' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo 'SESSION_DRIVER=file' >> /var/www/startup.sh && \
    echo 'SESSION_LIFETIME=120' >> /var/www/startup.sh && \
    echo 'SESSION_ENCRYPT=false' >> /var/www/startup.sh && \
    echo 'SESSION_PATH=/' >> /var/www/startup.sh && \
    echo 'SESSION_DOMAIN=null' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo 'MAIL_MAILER=log' >> /var/www/startup.sh && \
    echo 'EOF' >> /var/www/startup.sh && \
    echo '    echo "✅ .env file created"' >> /var/www/startup.sh && \
    echo 'else' >> /var/www/startup.sh && \
    echo '    echo "✅ .env file already exists"' >> /var/www/startup.sh && \
    echo 'fi' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo '# Generate APP_KEY if needed' >> /var/www/startup.sh && \
    echo 'echo "🔑 Checking APP_KEY..."' >> /var/www/startup.sh && \
    echo 'if ! grep -q "APP_KEY=base64:" .env; then' >> /var/www/startup.sh && \
    echo '    echo "Generating new APP_KEY..."' >> /var/www/startup.sh && \
    echo '    php artisan key:generate --force --no-interaction' >> /var/www/startup.sh && \
    echo '    echo "✅ APP_KEY generated"' >> /var/www/startup.sh && \
    echo 'else' >> /var/www/startup.sh && \
    echo '    echo "✅ APP_KEY already exists"' >> /var/www/startup.sh && \
    echo 'fi' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo '# Test basic Laravel functionality' >> /var/www/startup.sh && \
    echo 'echo "🧪 Testing Laravel..."' >> /var/www/startup.sh && \
    echo 'php artisan --version || {' >> /var/www/startup.sh && \
    echo '    echo "❌ Laravel not responding!"' >> /var/www/startup.sh && \
    echo '    exit 1' >> /var/www/startup.sh && \
    echo '}' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo '# Clear all caches' >> /var/www/startup.sh && \
    echo 'echo "🧹 Clearing caches..."' >> /var/www/startup.sh && \
    echo 'php artisan cache:clear --no-interaction || echo "⚠️ Cache clear failed"' >> /var/www/startup.sh && \
    echo 'php artisan config:clear --no-interaction || echo "⚠️ Config clear failed"' >> /var/www/startup.sh && \
    echo 'php artisan route:clear --no-interaction || echo "⚠️ Route clear failed"' >> /var/www/startup.sh && \
    echo 'php artisan view:clear --no-interaction || echo "⚠️ View clear failed"' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo '# Setup database' >> /var/www/startup.sh && \
    echo 'echo "💾 Setting up SQLite database..."' >> /var/www/startup.sh && \
    echo 'mkdir -p /var/www/database' >> /var/www/startup.sh && \
    echo 'touch /var/www/database/database.sqlite' >> /var/www/startup.sh && \
    echo 'chmod 664 /var/www/database/database.sqlite' >> /var/www/startup.sh && \
    echo 'chown www-data:www-data /var/www/database/database.sqlite' >> /var/www/startup.sh && \
    echo 'echo "✅ SQLite file ready"' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo '# Test database connection' >> /var/www/startup.sh && \
    echo 'echo "🔌 Testing database connection..."' >> /var/www/startup.sh && \
    echo 'php artisan db:show --database=sqlite || {' >> /var/www/startup.sh && \
    echo '    echo "❌ Database connection failed!"' >> /var/www/startup.sh && \
    echo '    exit 1' >> /var/www/startup.sh && \
    echo '}' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo '# Run database migrations' >> /var/www/startup.sh && \
    echo 'echo "🗄️ Running database migrations..."' >> /var/www/startup.sh && \
    echo 'php artisan migrate:fresh --force --no-interaction --seed 2>&1 || {' >> /var/www/startup.sh && \
    echo '    echo "⚠️ Fresh migration failed, trying regular migration..."' >> /var/www/startup.sh && \
    echo '    php artisan migrate --force --no-interaction 2>&1 || {' >> /var/www/startup.sh && \
    echo '        echo "❌ All migrations failed!"' >> /var/www/startup.sh && \
    echo '        exit 1' >> /var/www/startup.sh && \
    echo '    }' >> /var/www/startup.sh && \
    echo '}' >> /var/www/startup.sh && \
    echo 'echo "✅ Database migrations completed"' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo '# Cache configurations for production' >> /var/www/startup.sh && \
    echo 'echo "⚡ Caching configurations..."' >> /var/www/startup.sh && \
    echo 'php artisan config:cache --no-interaction' >> /var/www/startup.sh && \
    echo 'php artisan route:cache --no-interaction' >> /var/www/startup.sh && \
    echo 'php artisan view:cache --no-interaction' >> /var/www/startup.sh && \
    echo 'echo "✅ Configurations cached"' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo '# Final health check' >> /var/www/startup.sh && \
    echo 'echo "🏥 Final health check..."' >> /var/www/startup.sh && \
    echo 'php artisan tinker --execute="echo \"Laravel is healthy!\";" || {' >> /var/www/startup.sh && \
    echo '    echo "❌ Health check failed!"' >> /var/www/startup.sh && \
    echo '    exit 1' >> /var/www/startup.sh && \
    echo '}' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo 'echo "🎉 Laravel setup completed successfully!"' >> /var/www/startup.sh && \
    echo 'echo "🌐 Starting web server on port ${PORT:-10000}..."' >> /var/www/startup.sh && \
    echo '' >> /var/www/startup.sh && \
    echo '# Start the Laravel server' >> /var/www/startup.sh && \
    echo 'exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000} --no-interaction' >> /var/www/startup.sh

# Make the script executable
RUN chmod +x /var/www/startup.sh

EXPOSE 10000

# Use the startup script as the main command
CMD ["/var/www/startup.sh"] 