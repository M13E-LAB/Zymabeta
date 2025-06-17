<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>ZYMA - Capturer mon repas</title>
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

        .camera-container {
            max-width: 430px;
            margin: 0 auto;
            min-height: 100vh;
            background: #000000;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* Status bar */
        .status-bar {
            height: 44px;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            font-size: 14px;
            font-weight: 600;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 200;
        }

        /* Header avec boutons overlay */
        .camera-header {
            position: absolute;
            top: 44px;
            left: 0;
            right: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
        }

        .header-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 20px;
            text-decoration: none;
            color: white;
        }

        .header-btn:active {
            transform: scale(0.9);
            background: rgba(0, 0, 0, 0.8);
        }

        .header-btn.active {
            background: rgba(255, 215, 0, 0.8);
            color: #000000;
        }

        /* Camera preview area */
        .camera-preview {
            flex: 1;
            background: #1a1a1a;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            min-height: 70vh;
            overflow: hidden;
        }

        /* Real camera video */
        .camera-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        /* Canvas for capturing photos */
        .capture-canvas {
            display: none;
        }

        .preview-placeholder {
            text-align: center;
            color: #888888;
        }

        .preview-icon {
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .preview-text {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .preview-subtitle {
            font-size: 14px;
            opacity: 0.7;
        }

        /* Image preview when uploaded */
        .image-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        /* Camera mode indicator */
        .camera-mode {
            position: absolute;
            top: 120px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(20px);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            z-index: 50;
        }

        /* Camera controls */
        .camera-controls {
            background: #000000;
            padding: 30px 20px 40px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* Mode switcher */
        .mode-switcher {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-bottom: 8px;
        }

        .mode-btn {
            padding: 12px 24px;
            border-radius: 20px;
            background: #1a1a1a;
            border: 1px solid #333333;
            color: #888888;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mode-btn.active {
            background: #007AFF;
            border-color: #007AFF;
            color: #ffffff;
        }

        .mode-btn:active {
            transform: scale(0.98);
        }

        /* Capture button */
        .capture-section {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 24px;
        }

        .capture-btn {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #ffffff;
            border: 4px solid #333333;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .capture-btn:active {
            transform: scale(0.95);
            background: #f0f0f0;
        }

        .capture-btn input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .capture-icon {
            font-size: 32px;
            color: #000000;
        }

        /* Camera control buttons on sides */
        .side-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .control-btn {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #1a1a1a;
            border: 1px solid #333333;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 20px;
        }

        .control-btn:active {
            transform: scale(0.95);
            background: #333333;
        }

        /* Quick actions */
        .quick-actions {
            display: flex;
            justify-content: space-around;
            gap: 16px;
        }

        .quick-btn {
            flex: 1;
            padding: 16px;
            border-radius: 20px;
            background: #1a1a1a;
            border: 1px solid #333333;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .quick-btn:active {
            transform: scale(0.98);
            background: #333333;
        }

        /* Permission prompt */
        .permission-prompt {
            text-align: center;
            padding: 40px 20px;
            color: #888888;
        }

        .permission-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.7;
        }

        .permission-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #ffffff;
        }

        .permission-text {
            font-size: 14px;
            margin-bottom: 24px;
        }

        .permission-btn {
            background: #007AFF;
            color: #ffffff;
            border: none;
            padding: 12px 24px;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .permission-btn:active {
            transform: scale(0.98);
            background: #0056CC;
        }

        /* Form overlay (slides up when image is selected) */
        .form-overlay {
            position: fixed;
            bottom: -100%;
            left: 0;
            right: 0;
            background: #000000;
            border-top-left-radius: 24px;
            border-top-right-radius: 24px;
            padding: 24px 20px 40px;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 300;
            max-height: 80vh;
            overflow-y: auto;
        }

        .form-overlay.show {
            bottom: 0;
        }

        .form-handle {
            width: 40px;
            height: 4px;
            background: #333333;
            border-radius: 2px;
            margin: 0 auto 24px;
        }

        .form-title {
            font-size: 24px;
            font-weight: 900;
            margin-bottom: 8px;
            text-align: center;
        }

        .form-subtitle {
            font-size: 16px;
            color: #888888;
            text-align: center;
            margin-bottom: 32px;
        }

        /* Form fields */
        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 16px 20px;
            border-radius: 16px;
            background: #1a1a1a;
            border: 1px solid #333333;
            color: #ffffff;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #007AFF;
            background: #222222;
        }

        .form-input::placeholder {
            color: #666666;
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        /* Post type selection */
        .post-types {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 24px;
        }

        .post-type {
            padding: 16px;
            border-radius: 16px;
            background: #1a1a1a;
            border: 2px solid #333333;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .post-type.selected {
            border-color: #007AFF;
            background: rgba(0, 122, 255, 0.1);
        }

        .post-type input {
            display: none;
        }

        .post-type-icon {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .post-type-label {
            font-size: 14px;
            font-weight: 600;
        }

        /* Submit button */
        .submit-btn {
            width: 100%;
            padding: 18px;
            border-radius: 20px;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            border: none;
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 16px;
        }

        .submit-btn:active {
            transform: scale(0.98);
        }

        .submit-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Loading state */
        .loading-spinner {
            width: 24px;
            height: 24px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive adjustments */
        @media (max-width: 390px) {
            .capture-btn {
                width: 70px;
                height: 70px;
            }
            
            .capture-icon {
                font-size: 28px;
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Camera flash effect */
        .flash-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #ffffff;
            opacity: 0;
            pointer-events: none;
            z-index: 999;
        }

        .flash-overlay.flash {
            animation: flashEffect 0.3s ease;
        }

        @keyframes flashEffect {
            0% { opacity: 0; }
            50% { opacity: 0.8; }
            100% { opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="camera-container">
        <!-- Status bar -->
        <div class="status-bar">
            <span>9:41</span>
            <span>ZYMA</span>
            <span>100%</span>
        </div>

        <!-- Header with overlay buttons -->
        <div class="camera-header">
            <a href="{{ route('social.feed') }}" class="header-btn">✕</a>
            <button class="header-btn" id="flashBtn">⚡</button>
            <button class="header-btn" id="switchCameraBtn">🔄</button>
    </div>
    
        <!-- Camera mode indicator -->
        <div class="camera-mode" id="cameraMode" style="display: none;">📸 Photo</div>

        <!-- Camera preview area -->
        <div class="camera-preview" id="cameraPreview">
            <!-- Real camera video stream -->
            <video id="cameraVideo" class="camera-video" autoplay muted playsinline></video>
            
            <!-- Canvas for capturing photos -->
            <canvas id="captureCanvas" class="capture-canvas"></canvas>
            
            <!-- Placeholder when no camera -->
            <div class="preview-placeholder" id="placeholder">
                <div class="preview-icon">📸</div>
                <div class="preview-text">Prêt à capturer ?</div>
                <div class="preview-subtitle">Choisissez caméra ou galerie ci-dessous</div>
                                </div>
            
            <!-- Image preview after capture/upload -->
            <img id="imagePreview" class="image-preview" alt="Aperçu">
            
            <!-- Permission prompt -->
            <div id="permissionPrompt" class="permission-prompt" style="display: none;">
                <div class="permission-icon">📷</div>
                <div class="permission-title">Accès à la caméra</div>
                <div class="permission-text">ZYMA a besoin d'accéder à votre caméra pour prendre des photos de vos repas</div>
                <button class="permission-btn" id="requestCameraBtn">Autoriser la caméra</button>
                                </div>
                            </div>

        <!-- Camera controls -->
        <div class="camera-controls">
            <!-- Mode switcher -->
            <div class="mode-switcher">
                <button class="mode-btn active" id="cameraModeBtn">📸 Caméra</button>
                <button class="mode-btn" id="galleryModeBtn">🖼️ Galerie</button>
                        </div>
                        
            <!-- Capture section with side controls -->
            <div class="capture-section">
                <div class="side-controls">
                    <div class="control-btn" id="timerBtn">⏱️</div>
                    
                    <div class="capture-btn" id="captureBtn">
                        <input type="file" id="imageInput" accept="image/*" style="display: none;">
                        <div class="capture-icon" id="captureIcon">📷</div>
                                </div>
                    
                    <div class="control-btn" id="gridBtn">⊞</div>
                            </div>
                        </div>
                        
            <!-- Quick actions -->
            <div class="quick-actions">
                <button class="quick-btn" id="retakeBtn" style="display: none;">
                    <span>🔄</span>
                    <span>Reprendre</span>
                </button>
                <button class="quick-btn" id="confirmBtn" style="display: none;">
                    <span>✓</span>
                    <span>Utiliser cette photo</span>
                </button>
                            </div>
                        </div>
                        
        <!-- Flash effect overlay -->
        <div class="flash-overlay" id="flashOverlay"></div>

        <!-- Form overlay -->
        <div class="form-overlay" id="formOverlay">
            <div class="form-handle"></div>
            <h2 class="form-title">Partagez votre repas</h2>
            <p class="form-subtitle">Ajoutez les détails de votre délicieux repas</p>

            <form action="{{ route('social.store') }}" method="POST" enctype="multipart/form-data" id="mealForm">
                @csrf
                <input type="hidden" name="captured_image" id="capturedImageData">
                
                <!-- Post type selection -->
                <div class="form-group">
                    <label class="form-label">Type de publication</label>
                    <div class="post-types">
                        <label class="post-type selected">
                            <input type="radio" name="post_type" value="meal" checked>
                            <div class="post-type-icon">🍽️</div>
                            <div class="post-type-label">Repas</div>
                        </label>
                        <label class="post-type">
                            <input type="radio" name="post_type" value="review">
                            <div class="post-type-icon">⭐</div>
                            <div class="post-type-label">Avis</div>
                                </label>
                            </div>
                            </div>

                <!-- Meal name -->
                <div class="form-group">
                    <label for="product_name" class="form-label">Nom du plat</label>
                    <input type="text" 
                           class="form-input" 
                           id="product_name" 
                           name="product_name" 
                           placeholder="Ex: Salade César, Burger maison..."
                           required>
                        </div>
                        
                <!-- Location/Restaurant -->
                <div class="form-group">
                    <label for="store_name" class="form-label">Lieu</label>
                    <input type="text" 
                           class="form-input" 
                           id="store_name" 
                           name="store_name" 
                           placeholder="Ex: Maison, Restaurant XY, Cantine..."
                           required>
                        </div>
                        
                <!-- Price -->
                <div class="form-group">
                    <label for="price" class="form-label">Prix (€)</label>
                    <input type="number" 
                           step="0.01" 
                           min="0" 
                           class="form-input" 
                           id="price" 
                           name="price" 
                           placeholder="0.00"
                           required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description" class="form-label">Description (optionnelle)</label>
                    <textarea class="form-input form-textarea" 
                              id="description" 
                              name="description" 
                              placeholder="Partagez votre avis, les ingrédients, votre ressenti..."></textarea>
                </div>

                <!-- Submit button -->
                <button type="submit" class="submit-btn" id="submitBtn">
                    <span id="submitText">🚀 Partager mon repas</span>
                    <div class="loading-spinner" id="loadingSpinner" style="display: none;"></div>
                </button>
            </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
            // DOM elements
            const cameraVideo = document.getElementById('cameraVideo');
            const captureCanvas = document.getElementById('captureCanvas');
            const imagePreview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('placeholder');
            const permissionPrompt = document.getElementById('permissionPrompt');
            const cameraMode = document.getElementById('cameraMode');
            const formOverlay = document.getElementById('formOverlay');
            const flashOverlay = document.getElementById('flashOverlay');
            
            // Buttons
            const cameraModeBtn = document.getElementById('cameraModeBtn');
            const galleryModeBtn = document.getElementById('galleryModeBtn');
            const captureBtn = document.getElementById('captureBtn');
            const captureIcon = document.getElementById('captureIcon');
            const imageInput = document.getElementById('imageInput');
            const retakeBtn = document.getElementById('retakeBtn');
            const confirmBtn = document.getElementById('confirmBtn');
            const flashBtn = document.getElementById('flashBtn');
            const switchCameraBtn = document.getElementById('switchCameraBtn');
            const requestCameraBtn = document.getElementById('requestCameraBtn');
            
            // Form elements
            const mealForm = document.getElementById('mealForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            
            // Camera state
            let currentStream = null;
            let currentMode = 'camera'; // 'camera' or 'gallery'
            let currentCamera = 'environment'; // 'user' (front) or 'environment' (back)
            let flashEnabled = false;
            let capturedImageBlob = null;

            // Initialize camera on load
            initializeCamera();

            // Mode switching
            cameraModeBtn.addEventListener('click', function() {
                switchToMode('camera');
            });

            galleryModeBtn.addEventListener('click', function() {
                switchToMode('gallery');
            });

            function switchToMode(mode) {
                currentMode = mode;
                
                // Update button states
                if (mode === 'camera') {
                    cameraModeBtn.classList.add('active');
                    galleryModeBtn.classList.remove('active');
                    captureIcon.textContent = '📷';
                    initializeCamera();
                } else {
                    galleryModeBtn.classList.add('active');
                    cameraModeBtn.classList.remove('active');
                    captureIcon.textContent = '🖼️';
                    stopCamera();
                    showPlaceholder();
                }
            }

            // Camera initialization
            function initializeCamera() {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    showPermissionPrompt('Votre navigateur ne supporte pas l\'accès à la caméra');
                    return;
                }

                placeholder.style.display = 'none';
                permissionPrompt.style.display = 'none';
                cameraMode.style.display = 'block';

                const constraints = {
                    video: {
                        facingMode: currentCamera,
                        width: { ideal: 1920 },
                        height: { ideal: 1080 }
                    }
                };

                navigator.mediaDevices.getUserMedia(constraints)
                    .then(function(stream) {
                        currentStream = stream;
                        cameraVideo.srcObject = stream;
                        cameraVideo.style.display = 'block';
                        placeholder.style.display = 'none';
                        permissionPrompt.style.display = 'none';
                    })
                    .catch(function(error) {
                        console.error('Camera access error:', error);
                        showPermissionPrompt('Impossible d\'accéder à la caméra');
                    });
            }

            function stopCamera() {
                if (currentStream) {
                    currentStream.getTracks().forEach(track => track.stop());
                    currentStream = null;
                }
                cameraVideo.style.display = 'none';
                cameraMode.style.display = 'none';
            }

            function showPlaceholder() {
                placeholder.style.display = 'block';
                imagePreview.style.display = 'none';
                retakeBtn.style.display = 'none';
                confirmBtn.style.display = 'none';
            }

            function showPermissionPrompt(message) {
                placeholder.style.display = 'none';
                permissionPrompt.style.display = 'block';
                permissionPrompt.querySelector('.permission-text').textContent = message;
            }

            // Camera controls
            requestCameraBtn.addEventListener('click', function() {
                initializeCamera();
            });

            switchCameraBtn.addEventListener('click', function() {
                currentCamera = currentCamera === 'environment' ? 'user' : 'environment';
                if (currentMode === 'camera') {
                    stopCamera();
                    initializeCamera();
                }
            });

            flashBtn.addEventListener('click', function() {
                flashEnabled = !flashEnabled;
                flashBtn.classList.toggle('active', flashEnabled);
            });

            // Capture functionality
            captureBtn.addEventListener('click', function() {
                if (currentMode === 'camera') {
                    capturePhoto();
                } else {
                    imageInput.click();
                }
            });

            function capturePhoto() {
                if (!currentStream) return;

                // Flash effect
                if (flashEnabled) {
                    flashOverlay.classList.add('flash');
                    setTimeout(() => flashOverlay.classList.remove('flash'), 300);
                }

                // Haptic feedback
                if (navigator.vibrate) {
                    navigator.vibrate(100);
                }

                // Capture from video
                const canvas = captureCanvas;
                const video = cameraVideo;
                
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0);
                
                // Convert to blob
                canvas.toBlob(function(blob) {
                    capturedImageBlob = blob;
                    
                    // Show preview
                    const url = URL.createObjectURL(blob);
                    imagePreview.src = url;
                    imagePreview.style.display = 'block';
                    cameraVideo.style.display = 'none';
                    cameraMode.style.display = 'none';
                    
                    // Show action buttons
                    retakeBtn.style.display = 'flex';
                    confirmBtn.style.display = 'flex';
                    
                    console.log('Camera photo captured, blob size:', blob.size, 'mode:', currentMode);
                    
                }, 'image/jpeg', 0.8);
            }

            // Gallery upload
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    capturedImageBlob = file;
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                        placeholder.style.display = 'none';
                        
                        // Show action buttons
                        retakeBtn.style.display = 'flex';
                        confirmBtn.style.display = 'flex';
                        
                        console.log('Gallery image selected, file size:', file.size, 'mode:', currentMode);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Action buttons
            retakeBtn.addEventListener('click', function() {
                imagePreview.style.display = 'none';
                retakeBtn.style.display = 'none';
                confirmBtn.style.display = 'none';
                capturedImageBlob = null;
                formOverlay.classList.remove('show');
                
                if (currentMode === 'camera') {
                    cameraVideo.style.display = 'block';
                    cameraMode.style.display = 'block';
                } else {
                    showPlaceholder();
                }
            });

            confirmBtn.addEventListener('click', function() {
                if (capturedImageBlob) {
                    // Show form overlay
                    setTimeout(() => {
                        formOverlay.classList.add('show');
                    }, 300);
                }
            });

            // Post type selection
            document.querySelectorAll('.post-type').forEach(type => {
                type.addEventListener('click', function() {
                    document.querySelectorAll('.post-type').forEach(t => t.classList.remove('selected'));
                    this.classList.add('selected');
                    this.querySelector('input').checked = true;
                });
            });

            // Form submission
            mealForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!capturedImageBlob) {
                    alert('Veuillez prendre ou sélectionner une photo');
                    return;
                }
                
                // Show loading state
                submitBtn.disabled = true;
                submitText.style.display = 'none';
                loadingSpinner.style.display = 'block';

                // Create FormData
                const formData = new FormData();
                formData.append('_token', document.querySelector('input[name="_token"]').value);
                formData.append('post_type', document.querySelector('input[name="post_type"]:checked').value);
                formData.append('product_name', document.getElementById('product_name').value);
                formData.append('store_name', document.getElementById('store_name').value);
                formData.append('price', document.getElementById('price').value);
                formData.append('description', document.getElementById('description').value);
                
                // Handle image differently based on source
                if (currentMode === 'camera' && capturedImageBlob instanceof Blob) {
                    // For camera-captured images, convert to base64
                const reader = new FileReader();
                reader.onload = function(e) {
                        formData.append('captured_image', e.target.result);
                        submitForm(formData);
                    };
                    reader.readAsDataURL(capturedImageBlob);
                } else {
                    // For gallery uploads, use the file directly
                    formData.append('image', capturedImageBlob, 'meal-photo.jpg');
                    submitForm(formData);
                }
            });

            function submitForm(formData) {
                // Submit to server
                fetch('{{ route("social.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Success - redirect to feed
                        window.location.href = '{{ route("social.feed") }}';
                    } else {
                        return response.text().then(text => {
                            console.error('Server response:', text);
                            throw new Error('Upload failed: ' + response.status);
                        });
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    // Reset button state
                    submitBtn.disabled = false;
                    submitText.style.display = 'block';
                    loadingSpinner.style.display = 'none';
                    
                    alert('Erreur lors de l\'envoi. Veuillez réessayer.');
                });
            }

            // Haptic feedback for mobile
            function addHapticFeedback(element) {
                element.addEventListener('touchstart', function() {
                    if (navigator.vibrate) {
                        navigator.vibrate(50);
                    }
                });
            }

            // Add haptic feedback to buttons
            [captureBtn, retakeBtn, confirmBtn, submitBtn].forEach(addHapticFeedback);

            // Prevent zoom on double tap
            let lastTouchEnd = 0;
            document.addEventListener('touchend', function (event) {
                let now = (new Date()).getTime();
                if (now - lastTouchEnd <= 300) {
                    event.preventDefault();
                }
                lastTouchEnd = now;
            }, false);

            // Cleanup on page unload
            window.addEventListener('beforeunload', function() {
                stopCamera();
            });
    });
</script>
</body>
</html> 