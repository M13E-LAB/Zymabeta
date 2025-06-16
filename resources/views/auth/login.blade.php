<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - ZYMA</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 430px;
            width: 100%;
            padding: 20px;
            background: #000000;
        }

        /* Logo Section */
        .logo-section {
            text-align: center;
            margin-bottom: 48px;
        }

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 32px;
            font-weight: 900;
        }

        .app-name {
            font-size: 32px;
            font-weight: 900;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .app-tagline {
            font-size: 16px;
            color: #888888;
        }

        /* Form */
        .login-form {
            background: #111111;
            border-radius: 24px;
            border: 1px solid #1a1a1a;
            padding: 32px 24px;
            margin-bottom: 24px;
        }

        .form-title {
            font-size: 24px;
            font-weight: 800;
            text-align: center;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #ffffff;
        }

        .form-input {
            width: 100%;
            padding: 16px 20px;
            border-radius: 16px;
            border: 1px solid #333333;
            background: #1a1a1a;
            color: #ffffff;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #007AFF;
            background: #222222;
            transform: scale(1.02);
        }

        .form-input.error {
            border-color: #FF3B30;
            background: rgba(255, 59, 48, 0.1);
        }

        .error-message {
            color: #FF3B30;
            font-size: 14px;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .remember-section {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
        }

        .checkbox {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            border: 2px solid #333333;
            background: #1a1a1a;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkbox:checked {
            background: #007AFF;
            border-color: #007AFF;
        }

        .checkbox-label {
            font-size: 16px;
            color: #cccccc;
            cursor: pointer;
        }

        .login-btn {
            width: 100%;
            padding: 18px 24px;
            border-radius: 16px;
            border: none;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 16px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 122, 255, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .forgot-password {
            text-align: center;
            margin-top: 16px;
        }

        .forgot-password a {
            color: #007AFF;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        /* Register Link */
        .register-section {
            text-align: center;
            padding: 24px;
            background: #111111;
            border-radius: 24px;
            border: 1px solid #1a1a1a;
        }

        .register-text {
            color: #888888;
            margin-bottom: 16px;
        }

        .register-btn {
            color: #007AFF;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            padding: 12px 24px;
            border: 1px solid #007AFF;
            border-radius: 12px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .register-btn:hover {
            background: #007AFF;
            color: #ffffff;
            transform: translateY(-1px);
        }

        /* Loading State */
        .loading {
            display: none;
            align-items: center;
            gap: 8px;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #333333;
            border-top: 2px solid #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-height: 700px) {
            .logo-section {
                margin-bottom: 24px;
            }
            
            .logo {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
            
            .app-name {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo">🍎</div>
            <h1 class="app-name">ZYMA</h1>
        </div>

        <!-- Login Form -->
        <div class="login-form">
            <h2 class="form-title">Connexion</h2>
            
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <input id="email" type="email" class="form-input @error('email') error @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                           placeholder="votre@email.com">
                    @error('email')
                        <div class="error-message">
                            ⚠️ {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input id="password" type="password" class="form-input @error('password') error @enderror" 
                           name="password" required autocomplete="current-password"
                           placeholder="••••••••">
                    @error('password')
                        <div class="error-message">
                            ⚠️ {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="remember-section">
                    <input class="checkbox" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="checkbox-label" for="remember">Se souvenir de moi</label>
                </div>

                <button type="submit" class="login-btn" id="loginBtn">
                    <span class="btn-text">Se connecter</span>
                    <div class="loading">
                        <div class="spinner"></div>
                        <span>Connexion...</span>
                    </div>
                </button>

                @if (Route::has('password.request'))
                    <div class="forgot-password">
                        <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Register Section -->
        @if (Route::has('register'))
        <div class="register-section">
            <p class="register-text">Pas encore de compte ?</p>
            <a href="{{ route('register') }}" class="register-btn">Créer un compte</a>
        </div>
        @endif
    </div>

    <script>
        // Animation des champs de formulaire
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.style.transform = 'scale(1)';
            });
        });

        // Gestion du formulaire de connexion
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const loginBtn = document.getElementById('loginBtn');
            const btnText = loginBtn.querySelector('.btn-text');
            const loading = loginBtn.querySelector('.loading');
            
            // Afficher l'état de chargement
            btnText.style.display = 'none';
            loading.style.display = 'flex';
            loginBtn.disabled = true;
            
            // Feedback haptique
            if (navigator.vibrate) {
                navigator.vibrate(100);
            }
        });

        // Animation du bouton
        document.querySelector('.login-btn').addEventListener('click', function() {
            if (!this.disabled) {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            }
        });

        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.logo-section, .login-form, .register-section');
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
