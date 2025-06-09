#!/bin/bash

echo "🚀 Déploiement de ZYMA en production..."

# 1. Mettre à jour le code
echo "📥 Récupération du code..."
git pull origin main

# 2. Installer les dépendances
echo "📦 Installation des dépendances..."
composer install --optimize-autoloader --no-dev

# 3. Optimisations Laravel
echo "⚡ Optimisations Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Migrations de base de données
echo "🗄️ Migrations de base de données..."
php artisan migrate --force

# 5. Stockage et permissions
echo "📁 Configuration du stockage..."
php artisan storage:link

# 6. Nettoyage du cache
echo "🧹 Nettoyage du cache..."
php artisan cache:clear
php artisan config:clear

echo "✅ Déploiement terminé !" 