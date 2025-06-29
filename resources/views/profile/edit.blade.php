<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le profil - ZYMA</title>
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
    display: flex;
    align-items: center;
            gap: 16px;
}

        .back-btn {
            width: 40px;
            height: 40px;
    border-radius: 50%;
            background: #1a1a1a;
            border: 1px solid #333333;
            color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
            text-decoration: none;
            font-size: 18px;
            transition: all 0.3s ease;
}

        .back-btn:hover {
            background: #333333;
            transform: scale(1.05);
}

        .header-title {
            font-size: 24px;
            font-weight: 800;
        }

        /* Form */
        .form-container {
            padding: 20px;
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
}

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        /* Avatar Section */
        .avatar-section {
            text-align: center;
            margin-bottom: 32px;
            padding: 24px;
            background: #111111;
            border-radius: 20px;
            border: 1px solid #1a1a1a;
}

        .current-avatar {
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
            transition: all 0.3s ease;
            overflow: hidden;
}

        .current-avatar.has-image {
            background: none;
}

        .change-avatar-btn {
            background: #007AFF;
            color: #ffffff;
            border: none;
            padding: 12px 24px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
}

        .change-avatar-btn:hover {
            background: #0056CC;
            transform: translateY(-1px);
        }

        /* Action Buttons */
        .action-buttons {
    display: flex;
            gap: 12px;
            margin-top: 32px;
            padding: 0 20px 100px;
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

        .btn-danger {
            background: #FF3B30;
            border-color: #FF3B30;
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
</style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <a href="{{ route('profile.show') }}" class="back-btn">←</a>
            <h1 class="header-title">Modifier le profil</h1>
        </div>

        <div class="form-container">
            <!-- Avatar Section -->
            <div class="avatar-section">
                <div class="current-avatar" id="avatarPreview">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <button class="change-avatar-btn" onclick="changeAvatar()">
                    📷 Changer la photo
                </button>
                <input type="file" id="avatarInput" accept="image/*" style="display: none;" onchange="previewAvatar(this)">
            </div>

            <!-- Form -->
            <form id="profileForm" method="POST" action="{{ route('profile.show') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                
                <div class="form-group">
                    <label class="form-label" for="name">Nom complet</label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ $user->name }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <input type="email" id="email" name="email" class="form-input" value="{{ $user->email }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="bio">Biographie</label>
                    <textarea id="bio" name="bio" class="form-input form-textarea" placeholder="Parlez-nous de votre parcours nutrition..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="goal">Objectif principal</label>
                    <select id="goal" name="goal" class="form-input">
                        <option value="">Sélectionnez votre objectif</option>
                        <option value="weight_loss">Perte de poids</option>
                        <option value="muscle_gain">Prise de masse</option>
                        <option value="maintenance">Maintien</option>
                        <option value="health">Améliorer ma santé</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button type="submit" form="profileForm" class="btn btn-primary">
                ✅ Sauvegarder
            </button>
            <a href="{{ route('profile.show') }}" class="btn">
                ❌ Annuler
            </a>
        </div>

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
        // Variable pour stocker l'image sélectionnée
        let selectedAvatarFile = null;

        // Fonction pour changer l'avatar
        function changeAvatar() {
            document.getElementById('avatarInput').click();
        }

        // Fonction pour prévisualiser l'avatar sélectionné
function previewAvatar(input) {
    if (input.files && input.files[0]) {
                selectedAvatarFile = input.files[0];
                
                // Vérifier la taille du fichier (max 5MB)
                if (selectedAvatarFile.size > 5 * 1024 * 1024) {
                    alert('L\'image est trop volumineuse. Veuillez choisir une image de moins de 5MB.');
                    return;
                }
                
                // Vérifier le type de fichier
                if (!selectedAvatarFile.type.startsWith('image/')) {
                    alert('Veuillez sélectionner un fichier image valide.');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const avatarPreview = document.getElementById('avatarPreview');
                    
                    // Remplacer le contenu par l'image
                    avatarPreview.innerHTML = '';
                    avatarPreview.style.backgroundImage = `url(${e.target.result})`;
                    avatarPreview.style.backgroundSize = 'cover';
                    avatarPreview.style.backgroundPosition = 'center';
                    avatarPreview.style.fontSize = '0'; // Cacher le texte
                    avatarPreview.classList.add('has-image');
                    
                    // Feedback visuel
                    avatarPreview.style.transform = 'scale(1.05)';
                    setTimeout(() => {
                        avatarPreview.style.transform = 'scale(1)';
                    }, 300);
            
                    // Changer le texte du bouton
                    const changeBtn = document.querySelector('.change-avatar-btn');
                    changeBtn.innerHTML = '✅ Photo sélectionnée';
                    changeBtn.style.background = '#10b981';
                    
                    // Feedback haptique
                    if (navigator.vibrate) {
                        navigator.vibrate(100);
        }
                };
                reader.readAsDataURL(selectedAvatarFile);
    }
}

        // Animation des champs de formulaire
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.style.transform = 'scale(1)';
            });
        });

        // Animation des boutons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
                
                if (navigator.vibrate) {
                    navigator.vibrate(50);
        }
            });
});

        // Validation du formulaire
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Simulation de sauvegarde
            const submitBtn = document.querySelector('.btn-primary');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '⏳ Sauvegarde...';
            submitBtn.disabled = true;
            
            // Créer FormData pour inclure l'image si sélectionnée
            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('_method', 'PATCH');
            formData.append('name', document.getElementById('name').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('bio', document.getElementById('bio').value);
            formData.append('goal', document.getElementById('goal').value);
            
            if (selectedAvatarFile) {
                formData.append('avatar', selectedAvatarFile);
            }
            
            // Simuler l'envoi avec un délai
            setTimeout(() => {
                if (selectedAvatarFile) {
                    alert('Profil et photo mis à jour avec succès !');
                } else {
                    alert('Profil mis à jour avec succès !');
                }
                window.location.href = '{{ route("profile.show") }}';
            }, 1500);
});
</script>
</body>
</html>
