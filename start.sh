#!/bin/bash

echo "🚀 Starting ZYMA Laravel App..."

# Create database if needed
mkdir -p database
touch database/database.sqlite

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Run migrations
php artisan migrate --force --no-interaction

echo "✅ Setup complete, starting server..."

# Start PHP server
exec php -S 0.0.0.0:$PORT -t public/ 