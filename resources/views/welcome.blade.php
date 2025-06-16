<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZYMA - Mangez mieux. Dépensez moins.</title>
    <meta name="description" content="L'application nutrition communautaire pour manger mieux et dépenser moins">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #0D1421 0%, #1a2332 50%, #2a3441 100%);
            color: #ffffff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .container {
            max-width: 430px;
            margin: 0 auto;
            min-height: 100vh;
            position: relative;
            background: #0D1421;
        }

        /* Navigation top */
        .nav-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            background: rgba(13, 20, 33, 0.95);
            backdrop-filter: blur(20px);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo {
            font-size: 28px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        .nav-buttons {
            display: flex;
            gap: 12px;
        }

        .nav-btn {
            padding: 8px 16px;
            border: 1px solid rgba(59, 130, 246, 0.5);
            border-radius: 20px;
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-btn:hover {
            background: rgba(59, 130, 246, 0.2);
            transform: translateY(-1px);
        }

        .nav-btn.primary {
            background: #3b82f6;
            color: #ffffff;
            border-color: #3b82f6;
        }

        /* Hero Section */
        .hero {
            padding: 60px 24px 40px;
            text-align: center;
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 20%, rgba(59, 130, 246, 0.15) 0%, transparent 50%);
            pointer-events: none;
        }

        .hero-title {
            font-size: 48px;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 16px;
            background: linear-gradient(135deg, #ffffff 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 40px;
            line-height: 1.5;
        }

        /* Search Container */
        .search-container {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 32px 24px;
            margin: 0 24px 40px;
            backdrop-filter: blur(20px);
        }

        .search-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 4px;
        }

        .search-tab {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 12px;
            background: transparent;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-tab.active {
            background: #3b82f6;
            color: #ffffff;
        }

        .search-input-container {
            position: relative;
            margin-bottom: 20px;
        }

        .search-input {
            width: 100%;
            padding: 16px 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-size: 16px;
            backdrop-filter: blur(10px);
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 16px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        .scanner-btn {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid #10b981;
            color: #10b981;
            margin-top: 12px;
        }

        /* Features */
        .features {
            padding: 40px 24px;
        }

        .features-title {
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 32px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 20px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            background: rgba(255, 255, 255, 0.08);
        }

        .feature-icon {
            font-size: 24px;
            margin-bottom: 12px;
        }

        .feature-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .feature-desc {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            line-height: 1.5;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 430px;
            background: rgba(13, 20, 33, 0.95);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 16px 24px 32px;
            display: flex;
            justify-content: space-around;
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }

        .bottom-nav-item.active {
            color: #3b82f6;
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

        .hero, .search-container, .features {
            animation: fadeInUp 0.6s ease forwards;
        }

        .features {
            animation-delay: 0.2s;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Navigation Top -->
        <nav class="nav-top">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="logo">ZYMA</div>
                <img src="{{ asset('images/etchelast-logo.jpg') }}" style="width: 20px; height: 20px; border-radius: 50%; object-fit: cover;" alt="Etchelast logo">
                <div style="font-size: 12px; color: rgba(255,255,255,0.6); font-weight: 500;">by Etchelast</div>
            </div>
            <div class="nav-buttons">
                <a href="{{ route('login') }}" class="nav-btn">Connexion</a>
                <a href="{{ route('register') }}" class="nav-btn primary">Inscription</a>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero">
            <!-- Logo Etchelast intégré -->
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%; margin-right: 16px; display: flex; align-items: center; justify-content: center; border: 2px solid rgba(255,255,255,0.2);">
                    <img src="{{ asset('images/etchelast-logo.jpg') }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" alt="Etchelast logo">
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 14px; color: rgba(255,255,255,0.6); margin-bottom: 4px;">Powered by</div>
                    <div style="font-size: 18px; font-weight: 700; color: white; letter-spacing: 0.5px;">ETCHELAST</div>
                </div>
            </div>

            <h1 style="color: white; font-size: 32px; font-weight: 800; text-align: center; margin-bottom: 12px; line-height: 1.2;">
                Bienvenue à bord les Etchelastiens !
            </h1>
            <p style="color: rgba(255,255,255,0.8); font-size: 18px; text-align: center; margin-bottom: 32px; line-height: 1.4;">
                La première app sociale de nutrition avec IA
            </p>
            
            <!-- Beta Call-to-Action -->
            <div style="background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05)); backdrop-filter: blur(20px); border-radius: 24px; padding: 24px; margin-bottom: 32px; border: 1px solid rgba(255,255,255,0.1);">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="font-size: 24px; margin-bottom: 8px;">🚀</div>
                    <div style="color: white; font-size: 20px; font-weight: 700; margin-bottom: 8px;">BÊTA OUVERTE</div>
                    <div style="color: rgba(255,255,255,0.8); font-size: 14px;">Rejoignez les premiers testeurs de ZYMA</div>
                </div>
                
                @guest
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <a href="{{ route('register') }}" style="flex: 1; min-width: 140px; background: linear-gradient(135deg, #007AFF, #0056CC); color: white; text-decoration: none; padding: 16px 24px; border-radius: 16px; font-weight: 600; text-align: center; font-size: 16px; border: none; transition: all 0.3s ease;">
                        S'inscrire
                    </a>
                    <a href="{{ route('login') }}" style="flex: 1; min-width: 140px; background: rgba(255,255,255,0.1); color: white; text-decoration: none; padding: 16px 24px; border-radius: 16px; font-weight: 600; text-align: center; font-size: 16px; border: 1px solid rgba(255,255,255,0.2); transition: all 0.3s ease;">
                        Se connecter
                    </a>
                </div>
                @else
                <a href="{{ route('social.feed') }}" style="display: block; background: linear-gradient(135deg, #007AFF, #0056CC); color: white; text-decoration: none; padding: 16px 24px; border-radius: 16px; font-weight: 600; text-align: center; font-size: 16px;">
                    Accéder à l'app
                </a>
                @endguest
            </div>
        </section>

        <!-- Search Container simplifié -->
        <div style="padding: 0 24px 40px; text-align: center;">
            <a href="{{ route('products.search') }}" 
               style="display: inline-block; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); 
                      color: white; text-decoration: none; padding: 20px 40px; border-radius: 20px; 
                      font-weight: 700; font-size: 18px; box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
                      transition: all 0.3s ease; border: 1px solid rgba(59, 130, 246, 0.3);">
                🔍 Recherche Avancée
            </a>
            <p style="margin-top: 16px; color: rgba(255,255,255,0.6); font-size: 14px;">
                Découvrez, comparez et partagez vos trouvailles nutrition
            </p>
        </div>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="#" class="bottom-nav-item active">
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
        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.hero, .search-container');
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