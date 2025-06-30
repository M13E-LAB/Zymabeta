<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Smimos - ZYMA</title>
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

        .league-title {
            font-size: 32px;
            font-weight: 900;
            margin-bottom: 8px;
        }

        .league-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .private-badge {
            background: #333333;
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .creator-info {
            font-size: 14px;
            color: #888888;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 24px;
            border: 1px solid #333333;
            background: #1a1a1a;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            user-select: none;
        }

        .btn-primary {
            background: #007AFF;
            border-color: #007AFF;
        }

        .btn-danger {
            background: #FF3B30;
            border-color: #FF3B30;
        }

        .btn:hover {
            transform: translateY(-1px);
            opacity: 0.8;
        }

        .btn:active {
            transform: translateY(0) scale(0.95);
            opacity: 0.9;
        }

        /* Marimba Section */
        .marimba-section {
            padding: 20px;
            background: #111111;
            border-radius: 16px;
            margin: 0 20px 20px;
            border: 1px solid #1a1a1a;
            font-style: italic;
            font-size: 16px;
            color: #888888;
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 8px;
            margin: 0 20px 20px;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .tab {
            padding: 12px 20px;
            border-radius: 24px;
            background: #1a1a1a;
            border: 1px solid #333333;
            color: #888888;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.3s ease;
            user-select: none;
        }

        .tab:hover {
            background: #222222;
            border-color: #444444;
        }

        .tab:active {
            transform: scale(0.95);
        }

        .tab.active {
            background: #007AFF;
            border-color: #007AFF;
            color: #ffffff;
        }

        /* Leaderboard */
        .leaderboard {
            padding: 0 20px;
        }

        .leaderboard-header {
            display: grid;
            grid-template-columns: 80px 1fr 120px 140px;
            gap: 16px;
            padding: 12px 20px;
            background: #111111;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 12px;
            font-weight: 600;
            color: #888888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .leaderboard-item {
            display: grid;
            grid-template-columns: 80px 1fr 120px 140px;
            gap: 16px;
            align-items: center;
            padding: 16px 20px;
            background: #111111;
            border-radius: 16px;
            margin-bottom: 12px;
            border: 1px solid #1a1a1a;
            transition: all 0.3s ease;
        }

        .leaderboard-item:hover {
            background: #1a1a1a;
            transform: translateY(-2px);
        }

        .position {
            font-size: 20px;
            font-weight: 900;
            color: #007AFF;
        }

        .member-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .member-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
        }

        .member-name {
            font-weight: 600;
            font-size: 15px;
        }

        .score {
            font-size: 18px;
            font-weight: 800;
            color: #ffffff;
        }

        .join-date {
            font-size: 13px;
            color: #888888;
        }

        /* Stats Section */
        .stats-section {
            padding: 20px;
            margin: 20px;
            background: #111111;
            border-radius: 16px;
            border: 1px solid #1a1a1a;
        }

        .stats-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #1a1a1a;
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .stat-label {
            color: #888888;
            font-size: 14px;
        }

        .stat-value {
            font-weight: 700;
            font-size: 16px;
        }

        /* Share Section */
        .share-section {
            padding: 20px;
            margin: 20px;
            background: #111111;
            border-radius: 16px;
            border: 1px solid #1a1a1a;
        }

        .share-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .share-description {
            color: #888888;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .invite-code {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #1a1a1a;
            border: 1px solid #333333;
            border-radius: 12px;
            padding: 16px;
        }

        .code-text {
            flex: 1;
            font-family: monospace;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .copy-btn {
            background: #333333;
            border: none;
            color: #ffffff;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .copy-btn:hover {
            background: #555555;
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

        .leaderboard-item {
            animation: fadeInUp 0.6s ease forwards;
        }

        /* Responsive ajustements */
        @media (max-width: 380px) {
            .leaderboard-header,
            .leaderboard-item {
                grid-template-columns: 60px 1fr 100px 120px;
                gap: 12px;
                padding: 12px 16px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1 class="league-title">Les Smimos</h1>
            <div class="league-info">
                <span class="private-badge">🔒 Ligue Privée</span>
                <span class="creator-info">Créée par {{ $league->creator->name }}</span>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('social.create') }}" class="btn btn-primary">
                    📷 Partager un repas
                </a>
                <button class="btn btn-danger" onclick="confirmLeaveLeague()">
                    🚪 Quitter la ligue
                </button>
                <button class="btn" onclick="scrollToInvite()">
                    👥 Inviter des amis
                </button>
            </div>
        </header>

        <!-- Marimba Section -->
        <div class="marimba-section">
            Marimba
        </div>
        
        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active">📅 Classement hebdo</button>
            <button class="tab">📆 Classement mensuel</button>
            <button class="tab">🏆 Classement général</button>
            <button class="tab">👥 Membres</button>
            </div>

        <!-- Leaderboard -->
        <div class="leaderboard">
            <div class="leaderboard-header">
                <div>POSITION</div>
                <div>MEMBRE</div>
                <div>SCORE TOTAL</div>
                <div>MEMBRE DEPUIS</div>
            </div>

            @foreach($weeklyLeaderboard as $member)
            <div class="leaderboard-item">
                <div class="position">{{ $member->pivot->position }}</div>
                <div class="member-info">
                    <div class="member-avatar">{{ substr($member->name, 0, 1) }}</div>
                    <div class="member-name">{{ $member->name }}</div>
                </div>
                <div class="score">{{ $member->pivot->weekly_score }}</div>
                <div class="join-date">30/05/2025</div>
            </div>
            @endforeach
        </div>
        
        <!-- Stats Section -->
        <div class="stats-section">
            <h3 class="stats-title">📊 Statistiques de la ligue</h3>
                            <div class="stat-item">
                <span class="stat-label">Membres</span>
                <span class="stat-value">1 / 50</span>
                            </div>
                            <div class="stat-item">
                <span class="stat-label">Score moyen</span>
                <span class="stat-value">18.0</span>
                            </div>
                            <div class="stat-item">
                <span class="stat-label">Posts cette semaine</span>
                <span class="stat-value">12</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Défis actifs</span>
                <span class="stat-value">3</span>
            </div>
                </div>
                
        <!-- Share Section -->
        <div class="share-section">
            <h3 class="share-title">🔗 Partager cette ligue</h3>
            <p class="share-description">Partagez ce code d'invitation avec vos amis pour qu'ils rejoignent votre ligue :</p>
            <div class="invite-code">
                <span class="code-text">vo8dswoWc9</span>
                <button class="copy-btn" onclick="copyToClipboard()">📋</button>
            </div>
        </div>

        <!-- Spacing for bottom nav -->
        <div style="height: 100px;"></div>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="{{ route('home') }}" class="bottom-nav-item">
                <div class="bottom-nav-icon">📱</div>
                <div class="bottom-nav-label">Communauté</div>
            </a>
            <a href="{{ route('leagues.index') }}" class="bottom-nav-item active">
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
        // Gestion des onglets
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                // Retirer la classe active de tous les onglets
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                
                // Ajouter la classe active à l'onglet cliqué
                tab.classList.add('active');
                
                // Feedback visuel immédiat
                tab.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    tab.style.transform = 'scale(1)';
                }, 150);
                
                // Animation des éléments du leaderboard
                const items = document.querySelectorAll('.leaderboard-item');
                items.forEach((item, index) => {
                    item.style.opacity = '0.5';
                    item.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, index * 100);
                });
                
                // Log pour debug
                console.log('Onglet cliqué:', tab.textContent);
            });
        });

        // Fonction de copie du code d'invitation
        function copyToClipboard() {
            const codeText = document.querySelector('.code-text').textContent;
            navigator.clipboard.writeText(codeText).then(() => {
                const btn = document.querySelector('.copy-btn');
                const originalText = btn.textContent;
                btn.textContent = '✓';
                btn.style.background = '#10b981';
                
                setTimeout(() => {
                    btn.textContent = originalText;
                    btn.style.background = '#333333';
                }, 2000);
            });
        }

        // Animation d'apparition des éléments
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

        document.querySelectorAll('.leaderboard-item, .stats-section, .share-section').forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'all 0.6s ease';
            element.style.animationDelay = `${index * 0.1}s`;
            observer.observe(element);
        });

        // Interaction avec les éléments du leaderboard
        document.querySelectorAll('.leaderboard-item').forEach(item => {
            item.addEventListener('click', () => {
                // Animation de sélection
                item.style.background = '#1a1a1a';
                setTimeout(() => {
                    item.style.background = '#111111';
                }, 200);
            });
        });

        // Fonction pour confirmer la sortie de la ligue
        function confirmLeaveLeague() {
            if (confirm('Êtes-vous sûr de vouloir quitter cette ligue ? Cette action est irréversible.')) {
                // Ici vous pouvez ajouter la logique pour quitter la ligue
                // Par exemple : window.location.href = '/leagues/LEAGUE_SLUG/leave';
                alert('Fonctionnalité de sortie de ligue à implémenter');
            }
        }

        // Fonction pour faire défiler vers la section d'invitation
        function scrollToInvite() {
            const shareSection = document.querySelector('.share-section');
            if (shareSection) {
                shareSection.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'center'
                });
                
                // Animation d'attention sur le code d'invitation
                const inviteCode = document.querySelector('.invite-code');
                if (inviteCode) {
                    inviteCode.style.animation = 'pulse 1s ease-in-out 2';
                }
            }
        }

        // Animation pulse pour le code d'invitation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
        `;
        document.head.appendChild(style);
</script>
</body>
</html> 