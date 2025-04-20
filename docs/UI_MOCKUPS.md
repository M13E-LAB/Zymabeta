# Maquettes UI de Zyma Social

Ce document présente les maquettes et le flux utilisateur pour les principales fonctionnalités du hub social Zyma.

## 1. Écran d'Accueil / Feed

L'écran d'accueil est un feed personnalisé qui combine:

```
┌──────────────────────────────────┐
│ [Logo] ZYMA           [🔍] [👤]  │ <- Barre de navigation
├──────────────────────────────────┤
│ ┌──────┐ ┌──────┐ ┌──────┐       │
│ │Scan  │ │Deals │ │Food  │ ...   │ <- Stories/Quick Actions
│ └──────┘ └──────┘ └──────┘       │
├──────────────────────────────────┤
│ ┌────────────────────────────┐   │
│ │🔔 Alerte Baisse de Prix    │   │ <- Alertes personnalisées
│ │Lait Entier -20% chez Leclerc   │
│ └────────────────────────────┘   │
├──────────────────────────────────┤
│ ┌────────────────────────────┐   │
│ │👤 Sophie                   │   │
│ │                            │   │
│ │📸 [Image d'un produit]     │   │ <- Post social
│ │                            │   │
│ │Nutella à 2.99€ chez Carrefour! │
│ │#bonplan #promo             │   │
│ │                            │   │
│ │❤️ 24   💬 5   🔗 Partager  │   │
│ └────────────────────────────┘   │
├──────────────────────────────────┤
│ ┌────────────────────────────┐   │
│ │👤 Marc                     │   │
│ │                            │   │
│ │📊 Comparaison de prix      │   │ <- Post de comparaison
│ │Café Carte Noire 500g       │   │
│ │                            │   │
│ │Intermarché: 5.99€          │   │
│ │Leclerc: 4.99€              │   │
│ │Auchan: 5.49€               │   │
│ │                            │   │
│ │❤️ 12   💬 3   🔗 Partager  │   │
│ └────────────────────────────┘   │
├──────────────────────────────────┤
│       [Chargement...]            │
└──────────────────────────────────┘
```

**Fonctionnalités clés:**
- Stories pour actions rapides (scan, bons plans, repas)
- Alertes de variations de prix sur produits suivis
- Fil d'actualité mêlant:
  - Bons plans partagés par la communauté
  - Comparaisons de prix
  - Posts de repas/produits
  - Revues de restaurants
- Interactions sociales (likes, commentaires, partage)
- Pull-to-refresh pour actualiser le feed

## 2. Scan de Ticket

```
┌──────────────────────────────────┐
│ [←] Scanner un ticket    [?]     │
├──────────────────────────────────┤
│                                  │
│                                  │
│      ┌──────────────────┐        │
│      │                  │        │
│      │                  │        │
│      │     [CAMERA]     │        │
│      │                  │        │
│      │                  │        │
│      └──────────────────┘        │
│                                  │
│                                  │
│   Placez votre ticket de caisse  │
│   dans le cadre et prenez une    │
│   photo nette                    │
│                                  │
│                                  │
│    [Galerie]     [📸 Capturer]   │
└──────────────────────────────────┘
```

Après le scan:

```
┌──────────────────────────────────┐
│ [←] Résultats du scan    [✓]     │
├──────────────────────────────────┤
│ ┌────────────────────────────┐   │
│ │   ✅ 12 produits identifiés    │
│ └────────────────────────────┘   │
├──────────────────────────────────┤
│ MAGASIN: Carrefour Express        │
│ DATE: 20/04/2025                 │
│ TOTAL: 42.56€                    │
├──────────────────────────────────┤
│ PRODUITS:                        │
│                                  │
│ ● Lait demi-écrémé 1L            │
│   1.15€ [Éditer]                 │
│                                  │
│ ● Œufs x6 bio                    │
│   2.45€ [Éditer]                 │
│                                  │
│ ● Pain de mie complet            │
│   1.85€ [Éditer]                 │
│   ...                            │
│                                  │
│ ┌────────────────────────────┐   │
│ │   🎁 +35 POINTS GAGNÉS!    │   │
│ │   💰 0.85€ DE CASHBACK     │   │
│ └────────────────────────────┘   │
│                                  │
│       [VALIDER LE TICKET]        │
└──────────────────────────────────┘
```

**Fonctionnalités clés:**
- Capture de ticket via caméra ou galerie
- OCR pour extraction automatique:
  - Magasin
  - Date
  - Liste de produits et prix
- Édition manuelle possible
- Attribution immédiate:
  - Points de gamification
  - Cashback éventuel
- Enregistrement dans l'historique personnel

## 3. Profil Utilisateur

```
┌──────────────────────────────────┐
│ [←] Profil          [⚙️]         │
├──────────────────────────────────┤
│  ┌─────┐                         │
│  │ 👤  │ Marie Dupont            │
│  └─────┘ @marie93                │
│                                  │
│  Foodie 🍔 Chasseuse de promos 🏷️ │
│  Paris 📍                        │
├──────────────────────────────────┤
│ NIVEAU: ÉCLAIREUR                │
│ ━━━━━━━━━━━━━━━━━▒▒▒ 68%         │
│ 340 points • Prochain: 500 pts   │
├──────────────────────────────────┤
│  🏆 BADGES                        │
│  [🔍]  [🛒]  [🥗]  [📊]  [➕]    │
├──────────────────────────────────┤
│ STATISTIQUES                     │
│ ┌─────────┐ ┌─────────┐ ┌─────┐  │
│ │   24    │ │   18    │ │ 156 │  │
│ │ Tickets │ │ Partages│ │ Pts │  │
│ └─────────┘ └─────────┘ └─────┘  │
├──────────────────────────────────┤
│ 💰 CASHBACK: 12.85€ [Retirer]    │
├──────────────────────────────────┤
│ 📌 MES BONS PLANS                │
│ ┌────────────────────────────┐   │
│ │ [Image] Yaourts Activia    │   │
│ │ 1.99€ au lieu de 3.45€     │   │
│ │ il y a 2 jours             │   │
│ └────────────────────────────┘   │
│ ... [Voir tous]                  │
└──────────────────────────────────┘
```

**Fonctionnalités clés:**
- Informations et statistiques de l'utilisateur
- Système de niveaux et progression
- Badges débloqués
- Solde de cashback disponible
- Historique des contributions
- Résumé des économies réalisées

## 4. Création d'un Bon Plan

```
┌──────────────────────────────────┐
│ [←] Nouveau bon plan   [Publier] │
├──────────────────────────────────┤
│ ┌────────────────────────────┐   │
│ │ [APPAREIL PHOTO / GALERIE] │   │
│ │       📸 Ajouter photos    │   │
│ └────────────────────────────┘   │
├──────────────────────────────────┤
│ PRODUIT                          │
│ ┌────────────────────────────┐   │
│ │ Rechercher ou scanner      │ 🔍│
│ └────────────────────────────┘   │
├──────────────────────────────────┤
│ PRIX                             │
│ ┌────────┐ ┌────────────────┐    │
│ │ 3.99€  │ │ Prix normal    │    │
│ └────────┘ │ 5.49€          │    │
│            └────────────────┘    │
├──────────────────────────────────┤
│ MAGASIN                          │
│ [📍] Monoprix Saint-Michel       │
│     (Détecté automatiquement)    │
├──────────────────────────────────┤
│ VALIDITÉ                         │
│ ┌────────────────────────────┐   │
│ │ Du 20/04 au 26/04/2025     │ 📅│
│ └────────────────────────────┘   │
├──────────────────────────────────┤
│ DESCRIPTION                      │
│ ┌────────────────────────────┐   │
│ │ Super promo sur le rayon   │   │
│ │ produits laitiers! Limite  │   │
│ │ de 5 par personne.         │   │
│ └────────────────────────────┘   │
├──────────────────────────────────┤
│ #bonplan #monoprix #lait         │
└──────────────────────────────────┘
```

**Fonctionnalités clés:**
- Upload de photos du produit/étiquette de prix
- Recherche de produit par nom ou scan du code-barres
- Détection automatique du magasin (géolocalisation)
- Comparaison avec le prix normal/historique
- Définition de la période de validité
- Description et hashtags pour meilleure découvrabilité

## 5. Dashboard Nutrition

```
┌──────────────────────────────────┐
│ [←] Ma nutrition      [⚙️]        │
├──────────────────────────────────┤
│ RÉSUMÉ DES 30 DERNIERS JOURS     │
│ ┌────────────────────────────┐   │
│ │       [GRAPHIQUE]          │   │
│ │  Répartition nutritionnelle│   │
│ └────────────────────────────┘   │
├──────────────────────────────────┤
│ SCORE NUTRITIONNEL               │
│                                  │
│ ┌────────────────────────────┐   │
│ │ Score Nutri-Score: B       │   │
│ │ ━━━━━━━━━━▒▒▒▒▒            │   │
│ └────────────────────────────┘   │
│                                  │
│ ┌────────────────────────────┐   │
│ │ Score NOVA: 2              │   │
│ │ ━━━━━━━━━━━━━━▒▒▒▒▒▒       │   │
│ └────────────────────────────┘   │
├──────────────────────────────────┤
│ AMÉLIORER VOTRE ALIMENTATION     │
│                                  │
│ ┌────────────────────────────┐   │
│ │ ⚠️ Trop de produits ultra- │   │
│ │   transformés détectés     │   │
│ │   [Voir alternatives]      │   │
│ └────────────────────────────┘   │
│                                  │
│ ┌────────────────────────────┐   │
│ │ 🍎 Vous pourriez augmenter │   │
│ │   votre consommation de    │   │
│ │   fruits frais             │   │
│ └────────────────────────────┘   │
└──────────────────────────────────┘
```

**Fonctionnalités clés:**
- Analyse des achats récents (via tickets scannés)
- Calcul de scores nutritionnels
- Visualisation de la répartition des groupes alimentaires
- Suggestions personnalisées pour améliorer l'alimentation
- Alternatives plus saines aux produits habituels
- Évolution dans le temps des habitudes alimentaires

## 6. Carte des Bons Plans

```
┌──────────────────────────────────┐
│ [←] Près de moi       [Filtres]  │
├──────────────────────────────────┤
│                                  │
│         [CARTE GOOGLE]           │
│                                  │
│     [🔴]  [🔴]  [🔵]           │
│                                  │
│              [📍]               │
│     [🔴]            [🔵]        │
│                                  │
│         [🔵]      [🔴]          │
│                                  │
├──────────────────────────────────┤
│ LÉGENDE:                         │
│ 🔴 Promotions    🔵 Magasins     │
├──────────────────────────────────┤
│ BONS PLANS À PROXIMITÉ           │
│                                  │
│ ┌────────────────────────────┐   │
│ │ Carrefour Market (450m)    │   │
│ │ 4 promos actives           │   │
│ └────────────────────────────┘   │
│                                  │
│ ┌────────────────────────────┐   │
│ │ Auchan (1.2km)             │   │
│ │ 7 promos actives           │   │
│ └────────────────────────────┘   │
└──────────────────────────────────┘
```

**Fonctionnalités clés:**
- Carte interactive des magasins et promotions
- Géolocalisation de l'utilisateur
- Filtres par:
  - Type de magasin
  - Distance
  - Catégories de produits
  - Pourcentage de réduction
- Liste des bons plans à proximité immédiate
- Itinéraire vers le magasin sélectionné

## Fonctionnalités Communes

- **Mode sombre / clair**
- **Navigation intuitive**:
  - Barre inférieure: Feed, Recherche, Scan, Bons Plans, Profil
  - Gestes de swipe pour navigation rapide
- **Notifications push**:
  - Alertes de prix
  - Nouveaux bons plans à proximité
  - Interactions sociales
  - Cashback obtenu
- **Personnalisation**:
  - Préférences alimentaires
  - Magasins favoris
  - Produits suivis
  - Rayon d'intérêt géographique

---

Ces maquettes représentent les principales interfaces utilisateur de Zyma Social. Les designs finaux seront créés par notre équipe UX/UI en suivant ces spécifications fonctionnelles. 