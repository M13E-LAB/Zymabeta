<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>ZYMA - Feed Social</title>
    <meta name="theme-color" content="#000000">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #000000;
            color: #ffffff;
            min-height: 100vh;
            overflow-x: hidden;
            user-select: none;
        }

        .app-container {
            max-width: 430px;
            margin: 0 auto;
            min-height: 100vh;
            background: #000000;
            position: relative;
        }

        /* Status bar simulation */
        .status-bar {
            height: 44px;
            background: #000000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            font-size: 14px;
            font-weight: 600;
        }

        /* Header optimisé mobile */
        .header {
            padding: 16px 20px;
            background: #000000;
            border-bottom: 1px solid #1a1a1a;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(20px);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .app-title {
            font-size: 24px;
            font-weight: 900;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .notification-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #1a1a1a;
            border: 1px solid #333333;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-btn:active {
            transform: scale(0.95);
            background: #333333;
        }

        /* BeReal prompt when not posted */
        .bereal-prompt {
            text-align: center;
            padding: 60px 20px;
            margin-top: 100px;
        }

        .prompt-icon {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            margin: 0 auto 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .prompt-title {
            font-size: 28px;
            font-weight: 900;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .prompt-subtitle {
            font-size: 18px;
            color: #888888;
            margin-bottom: 40px;
            line-height: 1.4;
        }

        .capture-btn {
            width: 200px;
            height: 56px;
            border-radius: 28px;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            border: none;
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-decoration: none;
        }

        .capture-btn:active {
            transform: scale(0.98);
        }

        /* User's today post */
        .my-post-section {
            padding: 20px;
            margin-bottom: 16px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #888888;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .my-post {
            background: #111111;
            border-radius: 20px;
            overflow: hidden;
            border: 2px solid #007AFF;
        }

        /* Feed posts */
        .feed-section {
            padding: 0 20px 120px;
        }

        .post {
            background: #111111;
            border-radius: 20px;
            margin-bottom: 20px;
            overflow: hidden;
            border: 1px solid #1a1a1a;
            transition: all 0.3s ease;
        }

        .post:active {
            transform: scale(0.98);
        }

        .post-image {
            width: 100%;
            height: 320px;
            object-fit: cover;
            display: block;
        }

        .post-content {
            padding: 20px;
        }

        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 16px;
            margin-right: 12px;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .post-time {
            font-size: 14px;
            color: #888888;
        }

        .meal-info {
            margin: 16px 0;
        }

        .meal-name {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .meal-details {
            font-size: 14px;
            color: #888888;
            margin-bottom: 16px;
        }

        /* AI Score display */
        .ai-score {
            background: linear-gradient(135deg, #007AFF20, #5856D620);
            border: 1px solid #007AFF40;
            border-radius: 16px;
            padding: 16px;
            margin: 16px 0;
        }

        .score-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .score-title {
            font-size: 14px;
            font-weight: 700;
            color: #007AFF;
        }

        .score-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }

        .score-item {
            text-align: center;
        }

        .score-value {
            font-size: 24px;
            font-weight: 900;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .score-label {
            font-size: 12px;
            color: #888888;
        }

        /* Post actions */
        .post-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #1a1a1a;
        }

        .action-group {
            display: flex;
            gap: 32px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: none;
            border: none;
            color: #888888;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 8px;
            border-radius: 12px;
        }

        .action-btn:active {
            background: #1a1a1a;
            transform: scale(0.95);
        }

        .action-btn.liked {
            color: #FF3B30;
        }

        /* Bottom navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 430px;
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(30px);
            border-top: 1px solid #1a1a1a;
            padding: 16px 24px 32px;
            display: flex;
            justify-content: space-around;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #888888;
            transition: all 0.3s ease;
            padding: 8px;
            border-radius: 12px;
        }

        .nav-item.active {
            color: #007AFF;
        }

        .nav-item:active {
            transform: scale(0.95);
            background: #1a1a1a;
        }

        .nav-icon {
            font-size: 24px;
            margin-bottom: 4px;
        }

        .nav-label {
            font-size: 12px;
            font-weight: 600;
        }

        /* Responsive adjustments */
        @media (max-width: 390px) {
            .prompt-title {
                font-size: 24px;
            }
            
            .capture-btn {
                width: 180px;
                height: 52px;
                font-size: 16px;
            }
        }

        /* Loading animation */
        .loading {
            text-align: center;
            padding: 40px;
            color: #888888;
        }

        .spinner {
            width: 32px;
            height: 32px;
            border: 3px solid #1a1a1a;
            border-top: 3px solid #007AFF;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 16px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Status bar simulation -->
        <div class="status-bar">
            <span>9:41</span>
            <span>ZYMA</span>
            <span>100%</span>
        </div>

        <!-- Header -->
        <header class="header">
            <div class="header-top">
                <h1 class="app-title">ZYMA</h1>
                <button class="notification-btn">🔔</button>
            </div>
        </header>

        @if(!$hasPostedToday)
            <!-- BeReal style prompt -->
            <div class="bereal-prompt">
                <div class="prompt-icon">📸</div>
                <h2 class="prompt-title">Il est temps de poster !</h2>
                <p class="prompt-subtitle">Partagez votre repas pour découvrir ceux de vos amis aujourd'hui</p>
                <a href="{{ route('social.create') }}" class="capture-btn">
                    <span>📷</span>
                    Capturer mon repas
                </a>
            </div>
        @else
            <!-- User's post today -->
            @if($userTodayPost)
            <div class="my-post-section">
                <h3 class="section-title">Mon repas d'aujourd'hui</h3>
                <div class="my-post">
                    <img src="{{ $userTodayPost->image ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=320&fit=crop' }}" 
                         alt="{{ $userTodayPost->product_name }}" 
                         class="post-image">
                    <div class="post-content">
                        <div class="meal-info">
                            <h3 class="meal-name">{{ $userTodayPost->product_name }}</h3>
                            <p class="meal-details">{{ $userTodayPost->store_name }} • {{ $userTodayPost->created_at->format('H:i') }}</p>
                        </div>
                        
                        @if($userTodayPost->mealScore)
                        <div class="ai-score">
                            <div class="score-header">
                                <span>🤖</span>
                                <span class="score-title">Analyse IA</span>
                            </div>
                            <div class="score-grid">
                                <div class="score-item">
                                    <div class="score-value">{{ $userTodayPost->mealScore->health_score }}/100</div>
                                    <div class="score-label">Santé</div>
                                </div>
                                <div class="score-item">
                                    <div class="score-value">{{ $userTodayPost->mealScore->visual_score }}/100</div>
                                    <div class="score-label">Visuel</div>
                                </div>
                                <div class="score-item">
                                    <div class="score-value">{{ $userTodayPost->mealScore->total_score }}/100</div>
                                    <div class="score-label">Total</div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Friends' posts -->
            <div class="feed-section">
                <h3 class="section-title">Repas de vos amis</h3>
                
                @forelse($posts->where('user_id', '!=', auth()->id()) as $post)
                <article class="post">
                    <img src="{{ $post->image ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=320&fit=crop' }}" 
                         alt="{{ $post->product_name }}" 
                         class="post-image">
                    
                    <div class="post-content">
                        <div class="post-header">
                            <div class="user-avatar">
                                {{ substr($post->user->name, 0, 1) }}
                            </div>
                            <div class="user-info">
                                <div class="user-name">{{ $post->user->name }}</div>
                                <div class="post-time">{{ $post->created_at->diffForHumans() }}</div>
                            </div>
                        </div>

                        <div class="meal-info">
                            <h3 class="meal-name">{{ $post->product_name }}</h3>
                            <p class="meal-details">{{ $post->store_name }} • {{ number_format($post->price, 2) }} €</p>
                            @if($post->description)
                                <p class="meal-description">{{ $post->description }}</p>
                            @endif
                        </div>

                        @if($post->mealScore)
                        <div class="ai-score">
                            <div class="score-header">
                                <span>🤖</span>
                                <span class="score-title">Score IA</span>
                            </div>
                            <div class="score-grid">
                                <div class="score-item">
                                    <div class="score-value">{{ $post->mealScore->health_score }}/100</div>
                                    <div class="score-label">Santé</div>
                                </div>
                                <div class="score-item">
                                    <div class="score-value">{{ $post->mealScore->visual_score }}/100</div>
                                    <div class="score-label">Visuel</div>
                                </div>
                                <div class="score-item">
                                    <div class="score-value">{{ $post->mealScore->total_score }}/100</div>
                                    <div class="score-label">Total</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="post-actions">
                            <div class="action-group">
                                <button class="action-btn">
                                    ❤️ {{ $post->likes_count ?? 0 }}
                                </button>
                                <button class="action-btn">
                                    💬 {{ $post->comments_count ?? 0 }}
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
                @empty
                <div class="loading">
                    <div class="spinner"></div>
                    <p>Aucun ami n'a encore posté aujourd'hui...</p>
                </div>
                @endforelse
            </div>
        @endif

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="{{ route('social.feed') }}" class="nav-item active">
                <div class="nav-icon">🏠</div>
                <div class="nav-label">Accueil</div>
            </a>
            <a href="{{ route('social.create') }}" class="nav-item">
                <div class="nav-icon">📷</div>
                <div class="nav-label">Capturer</div>
            </a>
            <a href="{{ route('leagues.index') }}" class="nav-item">
                <div class="nav-icon">🏆</div>
                <div class="nav-label">Ligues</div>
            </a>
            <a href="{{ route('profile.show') }}" class="nav-item">
                <div class="nav-icon">👤</div>
                <div class="nav-label">Profil</div>
            </a>
        </nav>
    </div>

    <script>
        // Simple interactions for mobile
        document.addEventListener('DOMContentLoaded', function() {
            // Like functionality
            document.querySelectorAll('.action-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Add haptic feedback simulation
                    if (navigator.vibrate) {
                        navigator.vibrate(50);
                    }
                });
            });

            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';

            // Prevent zoom on double tap
            let lastTouchEnd = 0;
            document.addEventListener('touchend', function (event) {
                let now = (new Date()).getTime();
                if (now - lastTouchEnd <= 300) {
                    event.preventDefault();
                }
                lastTouchEnd = now;
            }, false);
        });
    </script>
</body>
</html> 