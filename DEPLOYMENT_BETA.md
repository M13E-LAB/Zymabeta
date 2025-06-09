# 🚀 Guide de Déploiement Beta - ZYMA

## 📋 Vue d'ensemble
ZYMA Beta sera déployé avec un **système d'invitation contrôlé** pour **50 beta testeurs maximum**.

## 🎯 Fonctionnalités Beta
- ✅ Scanner de codes-barres avec comparaison prix
- ✅ Partage de repas par ligues
- ✅ Feed social communautaire
- ✅ Scoring nutritionnel IA
- ✅ Système d'invitations limitées (50 max)

## 🌐 Déploiement Railway (Recommandé)

### Étape 1: Préparation
```bash
git add .
git commit -m "🚀 Beta system ready for deployment"
git push
```

### Étape 2: Railway Setup
1. **Créer compte** : https://railway.app
2. **New Project** → **Deploy from GitHub**
3. **Sélectionner** le repo `zymaProject-github-official`
4. **Ajouter MySQL** : Add Service → Database → MySQL

### Étape 3: Variables d'environnement
```env
APP_NAME=ZYMA
APP_ENV=production
APP_DEBUG=false
APP_KEY=[généré automatiquement]
APP_URL=[URL Railway]

# Base de données (auto-configurée par Railway)
DB_CONNECTION=mysql
DB_HOST=${{MYSQLHOST}}
DB_PORT=${{MYSQLPORT}}
DB_DATABASE=${{MYSQLDATABASE}}
DB_USERNAME=${{MYSQLUSER}}
DB_PASSWORD=${{MYSQLPASSWORD}}

# Cache et sessions
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database
```

### Étape 4: Première migration
Une fois déployé :
1. **Railway Dashboard** → **Deployments** → **View Logs**
2. **Migrations auto** : `php artisan migrate --force`

## 🎫 Gestion des Codes d'Invitation

### Générer les 50 codes beta
```bash
# En local d'abord pour tester
php artisan migrate
php artisan tinker

# Dans tinker:
for($i = 0; $i < 50; $i++) {
    \App\Models\BetaInvitation::create([
        'code' => \App\Models\BetaInvitation::generateUniqueCode()
    ]);
}
```

### Ou via l'interface web
1. Se connecter sur l'app en production
2. Aller sur `/beta/dashboard`
3. Cliquer "Générer 50 Codes"

## 📊 URLs importantes

### Public
- **Page d'accueil** : `https://zyma-beta.up.railway.app/`
- **Inscription** : `https://zyma-beta.up.railway.app/register`

### Admin (après connexion)
- **Dashboard Beta** : `/beta/dashboard`
- **Liste des codes** : `/beta/codes`
- **Générer codes** : `/beta/generate`

## 🔄 Workflow Beta

### Pour les beta testeurs
1. **Reçoivent un code** (ex: `BETA2024`)
2. **Vont sur le site** → Entrent le code
3. **Redirection** vers inscription
4. **S'inscrivent** normalement
5. **Accès complet** à toutes les fonctionnalités

### Pour toi (admin)
1. **Générer 50 codes** via dashboard
2. **Distribuer** aux beta testeurs
3. **Suivre** les inscriptions en temps réel
4. **Analyser** l'usage via dashboard

## 📈 Monitoring

### Métriques à suivre
- **Codes utilisés** / 50
- **Taux d'inscription** (codes → comptes créés)
- **Engagement** (connexions, posts, scans)
- **Feedback** beta testeurs

### Logs Railway
- **Build logs** : Voir les erreurs de déploiement
- **Runtime logs** : Voir les erreurs applicatives
- **Database logs** : Voir les requêtes lentes

## 🆙 Mise à jour

### Déploiement continu
```bash
# Nouvelle fonctionnalité
git add .
git commit -m "✨ Nouvelle feature"
git push

# Railway redéploie automatiquement
```

### Rollback si problème
```bash
# Railway Dashboard → Deployments → Rollback
```

## 💰 Coûts prévus

### Railway (recommandé)
- **Gratuit** : Premier mois
- **~$5/mois** : App + Base de données
- **Scaling auto** si besoin

### DigitalOcean (alternative)
- **~$12/mois** : App Platform
- **SSL inclus** et domaine personnalisé

## 🎯 Prochaines étapes après beta

1. **Analyser feedback** des 50 testeurs
2. **Corriger bugs** identifiés
3. **Optimiser performance** si nécessaire
4. **Lancement public** si tout va bien
5. **Domaine personnalisé** (zyma.app)

## ⚠️ Points d'attention

### Sécurité
- ✅ Codes d'invitation uniques
- ✅ Limitation à 50 utilisateurs
- ✅ Validation côté serveur
- ✅ Sessions sécurisées

### Performance
- ✅ API OpenFoodFacts optimisée
- ✅ Cache base de données
- ✅ Images optimisées
- ✅ Timeout appropriés

### Monitoring
- 📊 Dashboard beta intégré
- 🔍 Logs Railway en temps réel
- 📈 Suivi des métriques clés

---

**🚀 Prêt pour le lancement beta !** 