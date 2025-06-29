<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - ZYMA</title>
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

        .header-title {
            font-size: 28px;
            font-weight: 900;
            margin-bottom: 8px;
        }

        .header-subtitle {
            font-size: 16px;
            color: #888888;
            margin-bottom: 20px;
        }

        /* Profile Card */
        .profile-card {
            margin: 20px;
            background: #111111;
            border-radius: 20px;
            border: 1px solid #1a1a1a;
            overflow: hidden;
        }

        .profile-header {
            padding: 32px 24px;
            text-align: center;
            background: linear-gradient(135deg, #1a1a1a 0%, #111111 100%);
            position: relative;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 20%, rgba(0, 122, 255, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 36px;
            font-weight: 700;
            border: 4px solid rgba(0, 122, 255, 0.2);
            position: relative;
            z-index: 1;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .profile-username {
            font-size: 16px;
            color: #888888;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .level-badge {
            background: #007AFF;
            color: #ffffff;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        /* Stats */
        .profile-stats {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            padding: 0 24px 24px;
        }

        .stat-item {
            text-align: center;
            padding: 16px;
            background: #1a1a1a;
            border-radius: 16px;
            border: 1px solid #333333;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            background: #222222;
            transform: translateY(-2px);
        }

        .stat-value {
            display: block;
            font-size: 24px;
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

        /* Sections */
        .section {
            margin: 20px;
            background: #111111;
            border-radius: 20px;
            border: 1px solid #1a1a1a;
            overflow: hidden;
        }

        .section-header {
            padding: 20px 24px;
            border-bottom: 1px solid #1a1a1a;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-content {
            padding: 24px;
        }

        /* Level Progress */
        .level-section {
            text-align: center;
        }

        .level-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: conic-gradient(#007AFF 0deg 216deg, #333333 216deg 360deg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            position: relative;
        }

        .level-circle::before {
            content: '';
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #111111;
            position: absolute;
        }

        .level-info {
            position: relative;
            z-index: 1;
        }

        .current-points {
            font-size: 24px;
            font-weight: 800;
            color: #007AFF;
            display: block;
        }

        .level-name {
            font-size: 14px;
            color: #888888;
        }

        .next-level {
            margin-top: 16px;
            padding: 16px;
            background: #1a1a1a;
            border-radius: 12px;
            border: 1px solid #333333;
        }

        .next-level-text {
            font-size: 14px;
            color: #888888;
            margin-bottom: 4px;
        }

        .points-needed {
            font-size: 16px;
            font-weight: 600;
            color: #007AFF;
        }

        /* Achievements */
        .achievements-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .achievement {
            text-align: center;
            padding: 16px 12px;
            background: #1a1a1a;
            border-radius: 16px;
            border: 1px solid #333333;
            transition: all 0.3s ease;
        }

        .achievement:hover {
            background: #222222;
            transform: translateY(-2px);
        }

        .achievement.earned {
            background: rgba(0, 122, 255, 0.1);
            border-color: #007AFF;
        }

        .achievement-icon {
            font-size: 24px;
            margin-bottom: 8px;
            opacity: 0.5;
        }

        .achievement.earned .achievement-icon {
            opacity: 1;
        }

        .achievement-name {
            font-size: 12px;
            font-weight: 600;
            color: #888888;
        }

        .achievement.earned .achievement-name {
            color: #007AFF;
        }

        /* Recent Activity */
        .activity-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid #1a1a1a;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #1a1a1a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .activity-time {
            font-size: 13px;
            color: #888888;
        }

        .activity-points {
            font-size: 14px;
            font-weight: 600;
            color: #10b981;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 12px;
            margin: 20px;
        }

        .btn {
            flex: 1;
            padding: 16px 20px;
            border-radius: 16px;
            border: 1px solid #333333;
            background: #1a1a1a;
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: #007AFF;
            border-color: #007AFF;
        }

        .btn:hover {
            transform: translateY(-2px);
            opacity: 0.8;
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

        /* Settings Menu */
        .settings-menu {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .setting-item {
            display: flex;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #1a1a1a;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .setting-item:last-child {
            border-bottom: none;
        }

        .setting-item:hover {
            background: rgba(255, 255, 255, 0.05);
            margin: 0 -24px;
            padding: 16px 24px;
            border-radius: 12px;
        }

        .setting-item.logout-item {
            color: #FF3B30;
        }

        .setting-item.logout-item:hover {
            background: rgba(255, 59, 48, 0.1);
        }

        .setting-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #1a1a1a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-right: 16px;
        }

        .setting-content {
            flex: 1;
        }

        .setting-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .setting-subtitle {
            font-size: 14px;
            color: #888888;
        }

        .setting-arrow {
            font-size: 20px;
            color: #666666;
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

        .profile-card, .section {
            animation: fadeInUp 0.6s ease forwards;
        }

        .section:nth-child(even) {
            animation-delay: 0.1s;
        }

        .section:nth-child(odd) {
            animation-delay: 0.2s;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1 class="header-title">Mon Profil</h1>
            <p class="header-subtitle">Votre parcours nutrition personnalisé</p>
        </header>

        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h2 class="profile-name">{{ $user->name }}</h2>
                <p class="profile-username">@{{ strtolower(str_replace(' ', '', $user->name)) }}</p>
                <div class="level-badge">🎖️ Nutritionniste Débutant</div>
            </div>

            <div class="profile-stats">
                <div class="stat-item">
                    <span class="stat-value">12</span>
                    <span class="stat-label">Posts</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">340</span>
                    <span class="stat-label">Points</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">5</span>
                    <span class="stat-label">Badges</span>
                </div>
            </div>
        </div>

        <!-- Level Progress Section -->
        <div class="section">
            <div class="section-header">
                <h3 class="section-title">
                    📈 Progression
                </h3>
            </div>
            <div class="section-content level-section">
                <div class="level-circle">
                    <div class="level-info">
                        <span class="current-points">340</span>
                        <span class="level-name">Débutant</span>
                    </div>
                </div>
                <div class="next-level">
                    <div class="next-level-text">Prochain niveau : Éclaireur</div>
                    <div class="points-needed">160 points restants</div>
                </div>
            </div>
        </div>

        <!-- Achievements Section -->
        <div class="section">
            <div class="section-header">
                <h3 class="section-title">
                    🏆 Réalisations
                </h3>
            </div>
            <div class="section-content">
                <div class="achievements-grid">
                    <div class="achievement earned">
                        <div class="achievement-icon">🥇</div>
                        <div class="achievement-name">Premier Post</div>
                    </div>
                    <div class="achievement earned">
                        <div class="achievement-icon">📱</div>
                        <div class="achievement-name">Scanner Pro</div>
                    </div>
                    <div class="achievement earned">
                        <div class="achievement-icon">👥</div>
                        <div class="achievement-name">Social</div>
                    </div>
                    <div class="achievement">
                        <div class="achievement-icon">🎯</div>
                        <div class="achievement-name">Expert</div>
                    </div>
                    <div class="achievement">
                        <div class="achievement-icon">⭐</div>
                        <div class="achievement-name">Influenceur</div>
                    </div>
                    <div class="achievement">
                        <div class="achievement-icon">🏅</div>
                        <div class="achievement-name">Champion</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="section">
            <div class="section-header">
                <h3 class="section-title">
                    ⚡ Activité récente
                </h3>
            </div>
            <div class="section-content">
                <div class="activity-item">
                    <div class="activity-icon">📷</div>
                    <div class="activity-content">
                        <div class="activity-title">Photo partagée</div>
                        <div class="activity-time">Il y a 2 heures</div>
                    </div>
                    <div class="activity-points">+15 pts</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">🔍</div>
                    <div class="activity-content">
                        <div class="activity-title">Produit scanné</div>
                        <div class="activity-time">Il y a 5 heures</div>
                    </div>
                    <div class="activity-points">+10 pts</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">❤️</div>
                    <div class="activity-content">
                        <div class="activity-title">Post liké</div>
                        <div class="activity-time">Il y a 1 jour</div>
                    </div>
                    <div class="activity-points">+5 pts</div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                ⚙️ Modifier
            </a>
            <button onclick="shareProfile()" class="btn">
                📤 Partager
            </button>
        </div>

        <!-- Settings Section -->
        <div class="section">
            <div class="section-header">
                <h3 class="section-title">
                    ⚙️ Paramètres
                </h3>
            </div>
            <div class="section-content">
                <div class="settings-menu">
                    <div class="setting-item" onclick="showNotificationSettings()">
                        <div class="setting-icon">🔔</div>
                        <div class="setting-content">
                            <div class="setting-title">Notifications</div>
                            <div class="setting-subtitle">Gérer vos préférences</div>
                        </div>
                        <div class="setting-arrow">›</div>
                    </div>
                    
                    <div class="setting-item" onclick="showPrivacySettings()">
                        <div class="setting-icon">🔒</div>
                        <div class="setting-content">
                            <div class="setting-title">Confidentialité</div>
                            <div class="setting-subtitle">Contrôler vos données</div>
                        </div>
                        <div class="setting-arrow">›</div>
                    </div>
                    
                    <div class="setting-item" onclick="showAbout()">
                        <div class="setting-icon">ℹ️</div>
                        <div class="setting-content">
                            <div class="setting-title">À propos</div>
                            <div class="setting-subtitle">Version et informations</div>
                        </div>
                        <div class="setting-arrow">›</div>
                    </div>
                    
                    <div class="setting-item logout-item" onclick="confirmLogout()">
                        <div class="setting-icon">🚪</div>
                        <div class="setting-content">
                            <div class="setting-title">Se déconnecter</div>
                            <div class="setting-subtitle">Quitter votre session</div>
                        </div>
                        <div class="setting-arrow">›</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Spacing for bottom nav -->
        <div style="height: 100px;"></div>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="{{ route('home') }}" class="bottom-nav-item">
                <div class="bottom-nav-icon">🔍</div>
                <div class="bottom-nav-icon">📱</div>
                <div class="bottom-nav-label">Communauté</div>
            </a>
            <a href="{{ route('leagues.index') }}" class="bottom-nav-item">
                <div class="bottom-nav-icon">🏆</div>
                <div class="bottom-nav-label">Ligues</div>
            </a>
            <a href="{{ route('profile.show') }}" class="bottom-nav-item active">
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

        document.querySelectorAll('.section, .stat-item, .achievement').forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'all 0.6s ease';
            element.style.animationDelay = `${index * 0.1}s`;
            observer.observe(element);
        });

        // Interaction avec les achievements
        document.querySelectorAll('.achievement').forEach(achievement => {
            achievement.addEventListener('click', () => {
                achievement.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    achievement.style.transform = 'scale(1)';
                }, 150);
            });
        });

        // Animation du cercle de progression
        function animateProgressCircle() {
            const circle = document.querySelector('.level-circle');
            const currentProgress = 60; // 340/500 * 100 = 68%
            const degrees = (currentProgress / 100) * 360;
            
            circle.style.background = `conic-gradient(#007AFF 0deg ${degrees}deg, #333333 ${degrees}deg 360deg)`;
        }

        // Initialiser l'animation
        setTimeout(animateProgressCircle, 500);

        // Fonction de partage du profil
        function shareProfile() {
            if (navigator.share) {
                navigator.share({
                    title: 'Mon profil ZYMA',
                    text: 'Découvrez mon parcours nutrition sur ZYMA !',
                    url: window.location.href
                }).catch(console.error);
            } else {
                // Fallback pour les navigateurs qui ne supportent pas l'API Web Share
                const url = window.location.href;
                navigator.clipboard.writeText(url).then(() => {
                    alert('Lien du profil copié dans le presse-papiers !');
                }).catch(() => {
                    // Fallback ultime
                    const textArea = document.createElement('textarea');
                    textArea.value = url;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    alert('Lien du profil copié dans le presse-papiers !');
                });
            }
        }

        // Améliorer les interactions des boutons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Animation de clic
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
                
                // Feedback haptique si disponible
                if (navigator.vibrate) {
                    navigator.vibrate(50);
                }
            });
        });

        // Rendre les stats cliquables
        document.querySelectorAll('.stat-item').forEach((stat, index) => {
            stat.addEventListener('click', () => {
                const routes = [
                    '{{ route("profile.posts") }}', // Posts
                    '{{ route("profile.points") }}', // Points  
                    '{{ route("profile.badges") }}'  // Badges
                ];
                
                if (routes[index]) {
                    window.location.href = routes[index];
                }
            });
        });

        // Fonctions pour les paramètres
        function showNotificationSettings() {
            alert('Paramètres de notifications - À implémenter');
        }

        function showPrivacySettings() {
            alert('Paramètres de confidentialité - À implémenter');
        }

        function showAbout() {
            alert('ZYMA v1.0 Beta\nApplication de nutrition intelligente\n\nDéveloppé avec ❤️');
        }

        function confirmLogout() {
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                // Créer un formulaire pour la déconnexion POST
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("logout") }}';
                
                // Ajouter le token CSRF
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Ajouter au DOM et soumettre
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Animation des éléments de paramètres
        document.querySelectorAll('.setting-item').forEach(item => {
            item.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
                
                if (navigator.vibrate) {
                    navigator.vibrate(50);
                }
            });
        });
    </script>
</body>
</html> 