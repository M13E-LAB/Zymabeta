# 🥗 ZYMA - Social Nutrition Beta App

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![Beta](https://img.shields.io/badge/Status-Beta%20Testing-orange.svg)](#beta)
[![Deploy](https://img.shields.io/badge/Deploy-Railway%20|%20Heroku-success.svg)](#deployment)

> **L'app sociale qui révolutionne votre nutrition ! 🚀**

ZYMA est une plateforme sociale moderne qui combine tracking nutritionnel intelligent, objectifs personnalisés et communauté engagée. Actuellement en **beta testing** - rejoignez l'aventure !

## ✨ **Fonctionnalités Principales**

### 🎯 **Nutrition Intelligente**
- **Tracking automatique** des calories et macronutriments
- **Objectifs personnalisés** selon votre profil et objectifs
- **Analyse nutritionnelle** avancée avec recommandations
- **Base de données** alimentaire complète et française

### 👥 **Social & Communauté**
- **Profils utilisateurs** avec progression visible
- **Feed social** pour partager vos repas et succès
- **Système de suivi** entre utilisateurs (friends/following)
- **Challenges communautaires** et défis nutrition

### 📱 **Expérience Utilisateur**
- **Interface moderne** et intuitive
- **Dashboard personnel** avec statistiques visuelles
- **Notifications intelligentes** pour vos objectifs
- **Responsive design** - mobile first

## 🚀 **Beta Testing - Rejoignez-nous !**

### **Pourquoi participer ?**
- 🎁 **Accès gratuit** à vie aux fonctionnalités premium
- 🏆 **Influence directe** sur le développement
- 💰 **Récompenses** pour les feedbacks de qualité
- 🌟 **Badge Beta Tester** exclusif

### **Comment rejoindre ?**
1. **Téléchargez** l'app : [zyma-beta.herokuapp.com](https://zyma-beta-app-6a50456f2375.herokuapp.com)
2. **Inscrivez-vous** avec le code : `BETA2024`
3. **Testez** pendant 2 semaines minimum
4. **Partagez** vos retours via l'app ou nos réseaux

## 🛠 **Tech Stack**

```bash
Backend:      Laravel 10.x + PHP 8.1+
Database:     PostgreSQL
Frontend:     Blade + Tailwind CSS + Alpine.js
Deployment:   Railway / Heroku
Storage:      Cloud Storage (images)
API:          RESTful + JSON responses
```

## 📥 **Installation Développeur**

### **Prérequis**
- PHP 8.1+
- Composer
- Node.js 18+
- PostgreSQL

### **Installation Rapide**
```bash
# Clone le repo
git clone https://github.com/[username]/zyma-beta.git
cd zyma-beta

# Install dependencies
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed

# Assets
npm run build

# Launch
php artisan serve
```

## 🚀 **Déploiement**

### **Railway (Recommandé)**
[![Deploy on Railway](https://railway.app/button.svg)](https://railway.app/new/template?template=https://github.com/[username]/zyma-beta)

### **Heroku**
```bash
heroku create zyma-app
heroku addons:create heroku-postgresql:mini
git push heroku main
heroku run php artisan migrate
```

Voir [DEPLOYMENT_BETA.md](DEPLOYMENT_BETA.md) pour le guide complet.

## 📊 **Métriques Beta**

- 🎯 **Objectif**: 500 beta testeurs
- 📱 **Statut**: En cours de recrutement
- 💬 **Feedbacks**: Collectés via app + Discord
- 🏆 **Récompenses**: Points Beta convertibles

## 🤝 **Contribuer**

### **Beta Testeurs**
- Utilisez l'app quotidiennement
- Reportez les bugs via l'interface
- Proposez des améliorations
- Partagez sur vos réseaux

### **Développeurs**
1. Fork le projet
2. Créez votre branche (`git checkout -b feature/amazing-feature`)
3. Committez vos changements (`git commit -m 'Add amazing feature'`)
4. Push vers la branche (`git push origin feature/amazing-feature`)
5. Ouvrez une Pull Request

## 📞 **Contact & Support**

- 📧 **Email**: beta@zyma.app
- 💬 **Discord**: [ZYMA Beta Community](https://discord.gg/zyma-beta)
- 📱 **Instagram**: [@zyma.app](https://instagram.com/zyma.app)
- 🐦 **Twitter**: [@ZymaApp](https://twitter.com/ZymaApp)

## 📄 **Licence**

Ce projet est sous licence MIT - voir [LICENSE](LICENSE) pour les détails.

---

**Fait avec ❤️ par l'équipe ZYMA**

*Transformons ensemble notre rapport à la nutrition !* 🌟
