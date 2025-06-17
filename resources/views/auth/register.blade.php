<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - ZYMA</title>
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
            padding: 20px 0;
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
            margin-bottom: 32px;
        }

        .logo {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 24px;
            font-weight: 900;
        }

        .app-name {
            font-size: 28px;
            font-weight: 900;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .app-tagline {
            font-size: 14px;
            color: #888888;
        }

        /* Form */
        .register-form {
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
            margin-bottom: 20px;
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

        .password-strength {
            margin-top: 8px;
        }

        .strength-bar {
            height: 4px;
            background: #333333;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 4px;
        }

        .strength-fill {
            height: 100%;
            background: #FF3B30;
            width: 0%;
            transition: all 0.3s ease;
        }

        .strength-fill.weak { background: #FF3B30; }
        .strength-fill.medium { background: #FF9500; }
        .strength-fill.strong { background: #10b981; }

        .strength-text {
            font-size: 12px;
            color: #888888;
        }

        .terms-section {
            display: flex;
            align-items: flex-start;
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
            flex-shrink: 0;
            margin-top: 2px;
        }

        .checkbox:checked {
            background: #007AFF;
            border-color: #007AFF;
        }

        .terms-text {
            font-size: 14px;
            color: #cccccc;
            line-height: 1.4;
        }

        .terms-text a {
            color: #007AFF;
            text-decoration: none;
        }

        .terms-text a:hover {
            text-decoration: underline;
        }

        .register-btn {
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

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 122, 255, 0.3);
        }

        .register-btn:active {
            transform: translateY(0);
        }

        .register-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Login Section */
        .login-section {
            text-align: center;
            padding: 24px;
            background: #111111;
            border-radius: 24px;
            border: 1px solid #1a1a1a;
        }

        .login-text {
            color: #888888;
            margin-bottom: 16px;
        }

        .login-btn {
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

        .login-btn:hover {
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
        @media (max-height: 800px) {
            .logo-section {
                margin-bottom: 24px;
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

        <!-- Register Form -->
        <div class="register-form">
            <h2 class="form-title">Créer un compte</h2>

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        
                <div class="form-group">
                    <label class="form-label" for="name">Nom complet</label>
                    <input id="name" type="text" class="form-input @error('name') error @enderror" 
                           name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                           placeholder="Votre nom complet">
                            @error('name')
                        <div class="error-message">
                            ⚠️ {{ $message }}
                        </div>
                            @enderror
                        </div>

                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <input id="email" type="email" class="form-input @error('email') error @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email"
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
                           name="password" required autocomplete="new-password"
                           placeholder="8 caractères minimum">
                    <div class="password-strength" id="passwordStrength" style="display: none;">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <div class="strength-text" id="strengthText">Faible</div>
                        </div>
                    @error('password')
                        <div class="error-message">
                            ⚠️ {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
                    <input id="password_confirmation" type="password" class="form-input" 
                           name="password_confirmation" required autocomplete="new-password"
                           placeholder="Répétez votre mot de passe">
                </div>

                <div class="terms-section">
                    <input class="checkbox" type="checkbox" name="terms" id="terms" required>
                    <div class="terms-text">
                        J'accepte les <a href="#">conditions d'utilisation</a> et la <a href="#">politique de confidentialité</a> de ZYMA
                    </div>
                </div>
                
                <button type="submit" class="register-btn" id="registerBtn">
                    <span class="btn-text">Créer mon compte</span>
                    <div class="loading">
                        <div class="spinner"></div>
                        <span>Création...</span>
                    </div>
                </button>
            </form>
                </div>

        <!-- Login Section -->
        <div class="login-section">
            <p class="login-text">Vous avez déjà un compte ?</p>
            <a href="{{ route('login') }}" class="login-btn">Se connecter</a>
    </div>
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
        
        // Password strength meter
        const passwordInput = document.getElementById('password');
        const strengthContainer = document.getElementById('passwordStrength');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');
        
        passwordInput.addEventListener('input', function() {
            const value = this.value;
            
            if (value.length > 0) {
                strengthContainer.style.display = 'block';
                
                let strength = 0;
                let strengthClass = 'weak';
                let strengthLabel = 'Faible';
                
                // Length
                if (value.length >= 8) strength += 25;
                
                // Uppercase
                if (/[A-Z]/.test(value)) strength += 25;
                
                // Lowercase
                if (/[a-z]/.test(value)) strength += 25;
                
                // Numbers or symbols
                if (/[0-9]/.test(value) || /[^A-Za-z0-9]/.test(value)) strength += 25;
                
                // Update visual
                if (strength >= 75) {
                    strengthClass = 'strong';
                    strengthLabel = 'Fort';
                } else if (strength >= 50) {
                    strengthClass = 'medium';
                    strengthLabel = 'Moyen';
                }
                
                strengthFill.style.width = strength + '%';
                strengthFill.className = 'strength-fill ' + strengthClass;
                strengthText.textContent = strengthLabel;
            } else {
                strengthContainer.style.display = 'none';
            }
        });
        
        // Gestion du formulaire d'inscription
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const registerBtn = document.getElementById('registerBtn');
            const btnText = registerBtn.querySelector('.btn-text');
            const loading = registerBtn.querySelector('.loading');
            
            // Afficher l'état de chargement
            btnText.style.display = 'none';
            loading.style.display = 'flex';
            registerBtn.disabled = true;
            
            // Feedback haptique
            if (navigator.vibrate) {
                navigator.vibrate(100);
            }
        });

        // Animation du bouton
        document.querySelector('.register-btn').addEventListener('click', function() {
            if (!this.disabled) {
                this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            }
        });

        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.logo-section, .register-form, .login-section');
            elements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                element.style.transition = 'all 0.6s ease';
                
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 150);
        });
    });
</script>
</body>
</html>
