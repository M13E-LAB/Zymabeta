#!/bin/bash

echo "🚀 Démarrage de ZYMA..."

# Attendre que MySQL soit disponible
echo "⏳ Vérification de la base de données..."
php artisan tinker --execute="DB::connection()->getPdo();" > /dev/null 2>&1
while [ $? -ne 0 ]; do
  echo "⏳ En attente de MySQL..."
  sleep 2
  php artisan tinker --execute="DB::connection()->getPdo();" > /dev/null 2>&1
done

echo "✅ Base de données connectée"

# Exécuter les migrations
echo "🗄️ Exécution des migrations..."
php artisan migrate --force

# Créer le lien symbolique pour le storage
echo "📁 Configuration du stockage..."
php artisan storage:link

# Nettoyer le cache si nécessaire
echo "🧹 Nettoyage du cache..."
php artisan cache:clear || true
php artisan config:clear || true

echo "✅ Démarrage du serveur Laravel..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000} 