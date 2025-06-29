<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZYMA - Bienvenue à bord les Etchelastiens !</title>
    <meta name="description" content="L'application nutrition communautaire pour manger mieux et dépenser moins">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #1a2332 0%, #2a3441 50%, #3a4551 100%);
            color: #ffffff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .container {
            max-width: 430px;
            margin: 0 auto;
            min-height: 100vh;
            position: relative;
            background: linear-gradient(135deg, #1a2332 0%, #2a3441 50%, #3a4551 100%);
            display: flex;
            flex-direction: column;
        }

        /* Header avec logo ZYMA */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            background: rgba(26, 35, 50, 0.95);
            backdrop-filter: blur(20px);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            font-size: 28px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        .logo-badge {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .auth-buttons {
            display: flex;
            gap: 12px;
        }

        .auth-btn {
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

        .auth-btn:hover {
            background: rgba(59, 130, 246, 0.2);
            color: #ffffff;
        }

        .auth-btn.primary {
            background: #3b82f6;
            color: #ffffff;
        }

        .auth-btn.primary:hover {
            background: #2563eb;
        }

        /* Section principale */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 24px;
            text-align: center;
        }

        .powered-by {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
            opacity: 0.8;
        }

        .powered-logo {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .powered-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .powered-text {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
        }

        .powered-name {
            font-weight: 700;
            color: #ffffff;
        }

        .welcome-title {
            font-size: 48px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
            background: linear-gradient(135deg, #ffffff 0%, #a0a0a0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-subtitle {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 40px;
            line-height: 1.5;
        }

        .cta-button {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            padding: 16px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
            margin-bottom: 24px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(59, 130, 246, 0.4);
            color: #ffffff;
        }

        .feature-text {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 60px;
        }

        /* Navigation bottom */
        .bottom-nav {
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 20px 24px;
            background: rgba(26, 35, 50, 0.95);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
            padding: 8px;
            border-radius: 12px;
        }

        .nav-item:hover {
            color: #3b82f6;
            background: rgba(59, 130, 246, 0.1);
        }

        .nav-item.active {
            color: #3b82f6;
        }

        .nav-icon {
            font-size: 24px;
        }

        .nav-label {
            font-size: 12px;
            font-weight: 500;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .main-content > * {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .main-content > *:nth-child(1) { animation-delay: 0.1s; }
        .main-content > *:nth-child(2) { animation-delay: 0.2s; }
        .main-content > *:nth-child(3) { animation-delay: 0.3s; }
        .main-content > *:nth-child(4) { animation-delay: 0.4s; }
        .main-content > *:nth-child(5) { animation-delay: 0.5s; }

        /* Responsive */
        @media (max-width: 480px) {
            .welcome-title {
                font-size: 36px;
            }
            
            .header {
                padding: 16px 20px;
            }
            
            .main-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <div class="logo">ZYMA</div>
                <div class="logo-badge">by Etchelast</div>
            </div>
            <div class="auth-buttons">
                <a href="{{ route('login') }}" class="auth-btn">Connexion</a>
                <a href="{{ route('register') }}" class="auth-btn primary">Inscription</a>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="main-content">
            <div class="powered-by">
                <div class="powered-logo">
                    <img src="{{ asset('images/etchelast-logo.jpg') }}" alt="Etchelast Logo">
                </div>
                <div>
                    <div class="powered-text">Powered by</div>
                    <div class="powered-name">ETCHELAST</div>
                </div>
            </div>

            <h1 class="welcome-title">
                Bienvenue à bord<br>
                les<br>
                <span style="color: #3b82f6;">Etchelastiens !</span>
            </h1>

            <p class="welcome-subtitle">
                Rejoignez la communauté nutrition qui<br>
                révolutionne votre façon de manger sainement.
            </p>


            <p class="feature-text">
                Découvrez, comparez et partagez vos trouvailles nutrition
            </p>
        </div>

        <!-- Navigation bottom -->
        <div class="bottom-nav">
            <a href="{{ route('products.search') }}" class="nav-item active">
                <div class="nav-icon">🔍</div>
                <div class="nav-label">Découvrir</div>
            </a>
            <a href="{{ route('social.feed') }}" class="nav-item">
                <div class="nav-icon">👥</div>
                <div class="nav-label">Communauté</div>
            </a>
            <a href="{{ route('leagues.index') }}" class="nav-item">
                <div class="nav-icon">🏆</div>
                <div class="nav-label">Ligues</div>
            </a>
            <a href="{{ route('profile.show') }}" class="nav-item">
                <div class="nav-icon">👤</div>
                <div class="nav-label">Profil</div>
            </a>
        </div>
    </div>
</body>
</html> 