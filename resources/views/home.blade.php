<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - ZYMA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #000000;
            color: #ffffff;
            min-height: 100vh;
        }

        .container {
            max-width: 430px;
            margin: 0 auto;
            min-height: 100vh;
            background: #000000;
            position: relative;
            padding-bottom: 100px;
        }

        /* Header */
        .header {
            padding: 20px 20px 16px;
            background: #000000;
            border-bottom: 1px solid #1a1a1a;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-greeting {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .header-subtitle {
            font-size: 16px;
            color: #888888;
        }

        /* Quick Stats */
        .stats-section {
            padding: 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            margin-bottom: 24px;
    }
    
        .stat-card {
            background: #111111;
            border-radius: 16px;
            border: 1px solid #1a1a1a;
            padding: 16px 12px;
        text-align: center;
            transition: all 0.3s ease;
    }
    
        .stat-card:hover {
            background: #1a1a1a;
            transform: translateY(-2px);
        }

        .stat-value {
            font-size: 20px;
            font-weight: 800;
            color: #007AFF;
            margin-bottom: 4px;
    }
    
        .stat-label {
            font-size: 12px;
            color: #888888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
    }
    
        /* Feature Cards */
        .features-section {
            padding: 0 20px 20px;
        }

        .feature-card {
            background: #111111;
            border-radius: 20px;
            border: 1px solid #1a1a1a;
            margin-bottom: 16px;
            overflow: hidden;
        transition: all 0.3s ease;
        }

        .feature-card:hover {
            background: #1a1a1a;
            transform: translateY(-2px);
        }

        .feature-content {
            padding: 24px;
        display: flex;
        align-items: center;
            gap: 16px;
    }
    
        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(0, 122, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
    }
    
        .feature-info {
        flex: 1;
    }
    
        .feature-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .feature-description {
            font-size: 14px;
            color: #888888;
            line-height: 1.4;
        }

        .feature-arrow {
            font-size: 20px;
            color: #666666;
        }

        /* Quick Actions */
        .actions-section {
            padding: 0 20px 20px;
    }
    
        .section-title {
            font-size: 20px;
        font-weight: 700;
            margin-bottom: 16px;
            padding-left: 4px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
    }
    
        .action-btn {
            background: #111111;
            border: 1px solid #1a1a1a;
            border-radius: 16px;
            padding: 20px 16px;
            text-align: center;
            text-decoration: none;
            color: #ffffff;
            transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .action-btn:hover {
            background: #1a1a1a;
            color: #007AFF;
            transform: translateY(-2px);
        }

        .action-icon {
            font-size: 24px;
            margin-bottom: 4px;
        }

        .action-label {
            font-size: 14px;
            font-weight: 600;
        }

        /* Recent Activity */
        .activity-section {
            padding: 0 20px 20px;
        }

        .activity-card {
            background: #111111;
            border-radius: 20px;
            border: 1px solid #1a1a1a;
            padding: 24px;
    }
    
    .activity-empty {
        text-align: center;
            padding: 32px 16px;
            color: #888888;
        }

        .activity-empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .activity-empty-text {
            font-size: 16px;
            margin-bottom: 8px;
    }
    
        .activity-empty-subtext {
            font-size: 14px;
            opacity: 0.7;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 430px;
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(20px);
            border-top: 1px solid #1a1a1a;
            padding: 16px 24px 32px;
            display: flex;
            justify-content: space-around;
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #888888;
            transition: all 0.3s ease;
        }

        .bottom-nav-item.active {
            color: #007AFF;
        }

        .bottom-nav-icon {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .bottom-nav-label {
            font-size: 12px;
            font-weight: 500;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .feature-card, .stat-card, .action-btn {
            animation: fadeInUp 0.6s ease forwards;
        }

        .feature-card:nth-child(even) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.2s;
    }
</style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1 class="header-greeting">Salut {{ Auth::user()->name }} 👋</h1>
            <p class="header-subtitle">Prêt à découvrir de nouveaux produits ?</p>
        </header>

        <!-- Quick Stats -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ Auth::user()->points ?? 0 }}</div>
                    <div class="stat-label">Points</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ Auth::user()->posts()->count() ?? 0 }}</div>
                    <div class="stat-label">Posts</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ Auth::user()->badges()->count() ?? 0 }}</div>
                    <div class="stat-label">Badges</div>
                </div>
            </div>
        </section>

        <!-- Main Features -->
                    </div>
                    <div class="feature-arrow">›</div>
                </div>
            </a>
        </section>

        <!-- Quick Actions -->
        <section class="actions-section">
            <h2 class="section-title">Actions rapides</h2>
            <div class="actions-grid">
                <a href="{{ route('social.create') }}" class="action-btn">
                    <div class="action-icon">📸</div>
                    <div class="action-label">Partager</div>
                </a>
                <a href="{{ route('products.search') }}" class="action-btn">
                    <div class="action-icon">🔍</div>
                    <div class="action-label">Scanner</div>
                </a>
                <a href="{{ route('products.search') }}" class="action-btn">
                    <div class="action-icon">🔎</div>
                    <div class="action-label">Recherche avancée</div>
                </a>
                <a href="{{ route('profile.show') }}" class="action-btn">
                    <div class="action-icon">👤</div>
                    <div class="action-label">Profil</div>
                </a>
            </div>
        </section>

        <!-- Recent Activity -->
        <section class="activity-section">
            <h2 class="section-title">Activité récente</h2>
            <div class="activity-card">
                <div class="activity-empty">
                    <div class="activity-empty-icon">📋</div>
                    <div class="activity-empty-text">Aucune activité récente</div>
                    <div class="activity-empty-subtext">Commencez à partager pour voir votre activité ici</div>
                </div>
            </div>
        </section>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="{{ route('home') }}" class="bottom-nav-item active">
                <div class="bottom-nav-icon">🔍</div>
                <div class="bottom-nav-label">Découvrir</div>
            </a>
            <a href="{{ route('social.feed') }}" class="bottom-nav-item">
                <div class="bottom-nav-icon">📱</div>
                <div class="bottom-nav-label">Communauté</div>
            </a>
            <a href="{{ route('leagues.index') }}" class="bottom-nav-item">
                <div class="bottom-nav-icon">🏆</div>
                <div class="bottom-nav-label">Ligues</div>
            </a>
            <a href="{{ route('profile.show') }}" class="bottom-nav-item">
                <div class="bottom-nav-icon">👤</div>
                <div class="bottom-nav-label">Profil</div>
            </a>
        </nav>
    </div>

    <script>
        // Animation au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card, .stat-card, .action-btn').forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'all 0.6s ease';
            element.style.animationDelay = `${index * 0.1}s`;
            observer.observe(element);
        });

        // Interaction avec les cartes
        document.querySelectorAll('.feature-card, .action-btn, .stat-card').forEach(card => {
            card.addEventListener('click', function(e) {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
                
                if (navigator.vibrate) {
                    navigator.vibrate(50);
                }
            });
        });

        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.header, .stats-section, .features-section');
            elements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                element.style.transition = 'all 0.6s ease';
                
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
    </script>
</body>
</html>
// ✨ UX optimisée mobile
/* 📱 Optimisé pour iPhone */
