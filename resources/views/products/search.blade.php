@extends('layouts.app')

@section('title', 'Recherche - ZYMA')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Recherche - ZYMA</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<style>
/* Variables CSS et reset */
:root {
    --bg-dark: #111111;
    --bg-card: #1a1a1a;
    --bg-input: #222222;
    --text-primary: #ffffff;
    --text-secondary: #b0b0b0;
    --text-muted: #777777;
    --accent-blue: #3498db;
    --accent-green: #4CAF50;
    --accent-orange: #ff6b35;
    --border-dark: #333333;
    --gradient-primary: linear-gradient(135deg, #3498db 0%, #1d4ed8 100%);
    --shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    --radius: 16px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --font: 'Inter', sans-serif;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: var(--bg-dark);
    color: var(--text-primary);
    font-family: var(--font);
    overflow-x: hidden;
    min-height: 100vh;
}

/* Conteneur mobile principal */
.mobile-container {
    max-width: 430px;
    margin: 0 auto;
    background: var(--bg-dark);
    min-height: 100vh;
    position: relative;
    padding-bottom: 100px;
}

/* Header mobile */
.mobile-header {
    background: var(--bg-dark);
    padding: 24px 20px 20px;
    border-bottom: 1px solid var(--border-dark);
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.logo {
    font-size: 28px;
    font-weight: 800;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: -0.5px;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.back-btn {
    width: 44px;
    height: 44px;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 12px;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    font-size: 18px;
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-1px);
}

.search-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
    text-align: center;
}

/* Onglets de recherche */
.search-tabs {
    display: flex;
    background: rgba(255, 255, 255, 0.05);
    border-radius: var(--radius);
    padding: 4px;
    margin: 20px 20px 24px;
    gap: 4px;
}

.search-tab {
    flex: 1;
    background: transparent;
    border: none;
    color: var(--text-secondary);
    padding: 12px 8px;
    border-radius: calc(var(--radius) - 4px);
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
    text-align: center;
}

.search-tab.active {
    background: var(--accent-blue);
    color: white;
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
}

.search-tab:not(.active):hover {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
}

/* Contenu des onglets */
.tab-content {
    display: none;
    padding: 0 20px;
}

.tab-content.active {
    display: block;
}

/* Section de recherche */
.search-section {
    margin-bottom: 24px;
}

.search-form {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
}

.search-input {
    flex: 1;
    background: var(--bg-input);
    border: 1px solid var(--border-dark);
    border-radius: 12px;
    padding: 16px;
    color: var(--text-primary);
    font-size: 16px;
    font-family: var(--font);
    transition: var(--transition);
}

.search-input:focus {
    outline: none;
    border-color: var(--accent-blue);
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.search-input::placeholder {
    color: var(--text-muted);
}

.search-btn {
    background: var(--gradient-primary);
    border: none;
    border-radius: 12px;
    color: white;
    padding: 16px 20px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
    font-size: 14px;
}

.search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(52, 152, 219, 0.4);
}

/* Scanner */
.scanner-section {
    background: var(--bg-card);
    border-radius: var(--radius);
    padding: 20px;
    margin-bottom: 24px;
    border: 1px solid var(--border-dark);
}

.scanner-controls {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
}

.scanner-btn {
    flex: 1;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid var(--border-dark);
    border-radius: 12px;
    color: var(--text-primary);
    padding: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    font-size: 14px;
}

.scanner-btn:hover {
    background: rgba(255, 255, 255, 0.15);
}

.scanner-btn.active {
    background: var(--accent-green);
    border-color: var(--accent-green);
}

#camera-preview, #captured-image, #photo-preview {
    width: 100%;
    max-height: 200px;
    border-radius: 12px;
    object-fit: cover;
}

#camera-placeholder, #photo-placeholder {
    background: rgba(255, 255, 255, 0.05);
    border: 2px dashed var(--border-dark);
    border-radius: 12px;
    padding: 40px 20px;
    text-align: center;
    color: var(--text-muted);
    font-size: 14px;
}

/* Autocomplétion */
.autocomplete-results {
    background: var(--bg-card);
    border: 1px solid var(--border-dark);
    border-radius: 12px;
    margin-top: 8px;
    max-height: 200px;
    overflow-y: auto;
    position: relative;
    z-index: 50;
}

.autocomplete-item {
    padding: 12px 16px;
    cursor: pointer;
    border-bottom: 1px solid var(--border-dark);
    transition: var(--transition);
}

.autocomplete-item:last-child {
    border-bottom: none;
}

.autocomplete-item:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Résultats */
.results-section {
    background: var(--bg-card);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius);
    padding: 20px;
    margin-bottom: 24px;
}

.loading-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--text-secondary);
}

.loading-spinner {
    width: 32px;
    height: 32px;
    border: 3px solid rgba(255, 255, 255, 0.1);
    border-top: 3px solid var(--accent-blue);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 16px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.error-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--accent-orange);
}

.product-card {
    background: var(--bg-input);
    border: 1px solid var(--border-dark);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
    transition: var(--transition);
}

.product-card:hover {
    border-color: var(--accent-blue);
    transform: translateY(-2px);
}

.product-header {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
}

.product-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    object-fit: cover;
    background: rgba(255, 255, 255, 0.1);
}

.product-info {
    flex: 1;
}

.product-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
    font-size: 16px;
    line-height: 1.3;
}

.product-price {
    color: var(--accent-green);
    font-weight: 700;
    font-size: 18px;
}

.product-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid var(--border-dark);
}

.product-detail {
    font-size: 12px;
    color: var(--text-secondary);
}

.product-detail strong {
    color: var(--text-primary);
}

/* Animations */
.fade-in {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Bouton flotant retour */
.floating-back {
    position: fixed;
    bottom: 120px;
    right: 20px;
    width: 56px;
    height: 56px;
    background: var(--gradient-primary);
    border: none;
    border-radius: 50%;
    color: white;
    font-size: 24px;
    cursor: pointer;
    box-shadow: var(--shadow);
    transition: var(--transition);
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.floating-back:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(52, 152, 219, 0.4);
}

/* Responsive */
@media (max-width: 375px) {
    .mobile-container {
        max-width: 100%;
    }
    
    .mobile-header {
        padding: 20px 16px 16px;
    }
    
    .search-tabs {
        margin: 16px 16px 20px;
    }
    
    .tab-content {
        padding: 0 16px;
    }
    
    .search-input {
        padding: 14px;
        font-size: 16px;
    }
    
    .search-btn {
        padding: 14px 16px;
    }
}
    </style>
</head>

<body>
    <div class="mobile-container">
        <!-- Header Mobile -->
        <div class="mobile-header">
            <div class="header-top">
                <div class="logo">ZYMA</div>
                <div class="header-actions">
                    <a href="{{ url('/') }}" class="back-btn">←</a>
                </div>
            </div>
            <div class="search-title">Recherche Avancée</div>
        </div>

        <!-- Onglets de recherche -->
        <div class="search-tabs">
            <button class="search-tab active" data-tab="barcode">📊 Code-barres</button>
            <button class="search-tab" data-tab="name">🛍️ Nom</button>
            <button class="search-tab" data-tab="photo">📸 Photo</button>
        </div>

        <!-- Contenu Code-barres -->
        <div class="tab-content active" id="barcode-content">
            <div class="search-section">
                <form class="search-form" id="barcode-form">
                    @csrf
                    <input type="text" 
                           name="barcode" 
                           id="barcode-input"
                           class="search-input" 
                           placeholder="Scannez ou entrez un code-barres..." 
                           value="3017620422003">
                    <button type="submit" class="search-btn">Rechercher</button>
                </form>
            </div>

            <div class="scanner-section">
                <div class="scanner-controls">
                    <button type="button" class="scanner-btn" id="start-camera">📷 Scanner</button>
                    <button type="button" class="scanner-btn" id="stop-camera">⏹️ Arrêter</button>
                </div>
                <div id="camera-placeholder">
                    Cliquez sur "Scanner" pour démarrer la caméra
                </div>
                <video id="camera-preview" style="display: none;"></video>
                <img id="captured-image" style="display: none;">
            </div>
        </div>

        <!-- Contenu Nom du produit -->
        <div class="tab-content" id="name-content">
            <div class="search-section">
                <form class="search-form" id="name-form">
                    @csrf
                    <input type="text" 
                           name="name" 
                           id="name-input"
                           class="search-input" 
                           placeholder="Nom du produit..." 
                           autocomplete="off">
                    <button type="submit" class="search-btn">Rechercher</button>
                </form>
                <div id="autocomplete-results" class="autocomplete-results" style="display: none;"></div>
            </div>
        </div>

        <!-- Contenu Photo -->
        <div class="tab-content" id="photo-content">
            <div class="search-section">
                <div class="scanner-section">
                    <div class="scanner-controls">
                        <button type="button" class="scanner-btn" id="take-photo">📸 Prendre photo</button>
                        <input type="file" id="upload-photo" accept="image/*" style="display: none;">
                        <button type="button" class="scanner-btn" id="upload-btn">📁 Charger</button>
                    </div>
                    <div id="photo-placeholder">
                        Prenez une photo ou chargez une image du produit
                    </div>
                    <img id="photo-preview" style="display: none;">
                </div>
            </div>
        </div>

        <!-- Section des résultats -->
        <div class="results-section" id="results-container" style="display: none;">
            <div id="results-content"></div>
        </div>

        <!-- Bouton flotant retour -->
        <a href="{{ url('/') }}" class="floating-back">🏠</a>
    </div>

<script>
// Variables globales
let html5QrCode = null;
let isScanning = false;
let autocompleteTimeout = null;

// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Gestion des onglets
document.querySelectorAll('.search-tab').forEach(tab => {
    tab.addEventListener('click', () => {
            // Désactiver tous les onglets
        document.querySelectorAll('.search-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        // Activer l'onglet sélectionné
        tab.classList.add('active');
        const tabName = tab.getAttribute('data-tab');
        document.getElementById(tabName + '-content').classList.add('active');
        
        // Arrêter le scanner si on change d'onglet
        stopScanner();
        });
    });
    
// Recherche par code-barres
document.getElementById('barcode-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const barcode = document.getElementById('barcode-input').value.trim();
    if (barcode) {
        await searchProduct('barcode', barcode);
    }
});

// Recherche par nom
document.getElementById('name-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const name = document.getElementById('name-input').value.trim();
    if (name) {
        await searchProduct('name', name);
    }
});

// Autocomplétion
document.getElementById('name-input').addEventListener('input', function() {
    const query = this.value.trim();
    
    clearTimeout(autocompleteTimeout);
    
    if (query.length >= 2) {
        autocompleteTimeout = setTimeout(() => {
            fetchAutocomplete(query);
        }, 300);
            } else {
        hideAutocomplete();
    }
});

// Scanner de code-barres
document.getElementById('start-camera').addEventListener('click', startScanner);
document.getElementById('stop-camera').addEventListener('click', stopScanner);

// Photo
document.getElementById('upload-btn').addEventListener('click', () => {
    document.getElementById('upload-photo').click();
});

document.getElementById('upload-photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photo-preview');
            preview.src = e.target.result;
            preview.style.display = 'block';
            document.getElementById('photo-placeholder').style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});

// Fonction de recherche principale
async function searchProduct(type, query) {
    showLoading();
    
    try {
        let response;
        
        if (type === 'barcode') {
            // Recherche par code-barres via POST /fetch
            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('product_code', query);
            
            response = await fetch('/fetch', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
        } else if (type === 'name') {
            // Recherche par nom via GET /products/search
            response = await fetch(`/products/search?name=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
        }
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const html = await response.text();
        displayResults(html);
        
    } catch (error) {
        console.error('Erreur de recherche:', error);
        showError('Erreur lors de la recherche. Veuillez réessayer.');
    }
}

// Autocomplétion
async function fetchAutocomplete(query) {
    try {
        const response = await fetch(`/api/products/search?query=${encodeURIComponent(query)}`);
        
        if (response.ok) {
            const data = await response.json();
            showAutocomplete(data.suggestions || []);
        } else {
            hideAutocomplete();
        }
    } catch (error) {
        console.error('Erreur autocomplétion:', error);
        hideAutocomplete();
    }
}

// Affichage autocomplétion
function showAutocomplete(suggestions) {
    const container = document.getElementById('autocomplete-results');
    
    if (suggestions.length === 0) {
        hideAutocomplete();
        return;
    }
    
    container.innerHTML = suggestions.map(suggestion => 
        `<div class="autocomplete-item" onclick="selectAutocomplete('${suggestion}')">${suggestion}</div>`
    ).join('');
    
    container.style.display = 'block';
}

function hideAutocomplete() {
    document.getElementById('autocomplete-results').style.display = 'none';
}

function selectAutocomplete(value) {
    document.getElementById('name-input').value = value;
    hideAutocomplete();
    searchProduct('name', value);
}

// Scanner
async function startScanner() {
    if (isScanning) return;
    
    try {
        html5QrCode = new Html5Qrcode("camera-preview");
        
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        };
        
        await html5QrCode.start(
            { facingMode: "environment" },
            config,
            (decodedText) => {
                document.getElementById('barcode-input').value = decodedText;
                stopScanner();
                searchProduct('barcode', decodedText);
            },
            (errorMessage) => {
                // Erreur silencieuse
            }
        );
        
        isScanning = true;
        document.getElementById('camera-placeholder').style.display = 'none';
        document.getElementById('camera-preview').style.display = 'block';
        document.getElementById('start-camera').classList.add('active');
        
    } catch (error) {
        console.error('Erreur scanner:', error);
        showError('Impossible d\'accéder à la caméra');
    }
}

async function stopScanner() {
    if (html5QrCode && isScanning) {
        try {
            await html5QrCode.stop();
            html5QrCode = null;
            isScanning = false;
            
            document.getElementById('camera-preview').style.display = 'none';
            document.getElementById('camera-placeholder').style.display = 'block';
            document.getElementById('start-camera').classList.remove('active');
        } catch (error) {
            console.error('Erreur arrêt scanner:', error);
        }
    }
}

// Affichage des résultats
function showLoading() {
    const container = document.getElementById('results-container');
    container.innerHTML = `
        <div class="loading-state">
            <div class="loading-spinner"></div>
            <div>Recherche en cours...</div>
        </div>
    `;
    container.style.display = 'block';
    container.scrollIntoView({ behavior: 'smooth' });
}

function showError(message) {
    const container = document.getElementById('results-container');
    container.innerHTML = `
        <div class="error-state">
            <div style="font-size: 48px; margin-bottom: 16px;">⚠️</div>
            <div>${message}</div>
        </div>
    `;
    container.style.display = 'block';
}

function displayResults(html) {
    const container = document.getElementById('results-container');
    
    // Parser le HTML reçu
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    
    // Extraire les informations produit
    const productInfo = extractProductInfo(doc);
    
    if (productInfo) {
        container.innerHTML = formatProductCard(productInfo);
            } else {
        container.innerHTML = `
            <div class="error-state">
                <div style="font-size: 48px; margin-bottom: 16px;">🔍</div>
                <div>Aucun produit trouvé</div>
            </div>
        `;
    }
    
    container.style.display = 'block';
    container.classList.add('fade-in');
    container.scrollIntoView({ behavior: 'smooth' });
}

// Extraction des données produit
function extractProductInfo(doc) {
    try {
        // Vérifier si c'est une vue de produit valide
        const productNameEl = doc.querySelector('h1');
        if (!productNameEl) {
            return null;
        }
        
        const productName = productNameEl.textContent.trim();
        if (!productName || productName === 'Produit introuvable') {
            return null;
        }
        
        // Extraire l'image du produit
        const productImage = doc.querySelector('.product-image img')?.src;
        
        // Extraire les statistiques de prix
        let minPrice = null;
        let maxPrice = null;
        let avgPrice = null;
        
        // Chercher les prix dans les statistiques
        const priceStatItems = doc.querySelectorAll('.price-stat-item');
        priceStatItems.forEach(item => {
            const header = item.querySelector('h4')?.textContent?.trim();
            const priceText = item.querySelector('h3')?.textContent?.trim();
            
            if (header === 'Prix Min' && priceText) {
                minPrice = priceText;
            } else if (header === 'Prix Max' && priceText) {
                maxPrice = priceText;
            } else if (header === 'Prix Moyen' && priceText) {
                avgPrice = priceText;
            }
        });
        
        // Si pas trouvé dans les stats, chercher le prix principal
        if (!minPrice) {
            const mainPriceEl = doc.querySelector('.display-4.fw-bold');
            if (mainPriceEl) {
                minPrice = mainPriceEl.textContent.trim();
            }
        }
        
        // Extraire l'économie possible
        let economyText = null;
        const economyEl = doc.querySelector('.text-secondary .text-success');
        if (economyEl) {
            economyText = `Économie possible : ${economyEl.textContent.trim()}`;
        }
        
        // Extraire les magasins depuis les cartes
        const stores = [];
        const storeCards = doc.querySelectorAll('.card');
        
        storeCards.forEach(card => {
            // Nom du magasin
            const storeNameEl = card.querySelector('h4');
            if (!storeNameEl) return;
            
            const storeName = storeNameEl.textContent.trim();
            
            // Prix du magasin - chercher le h3 avec fw-bold dans la partie prix
            const priceSection = card.querySelector('.col-md-5');
            const storePriceEl = priceSection?.querySelector('h3.fw-bold');
            if (!storePriceEl) return;
            
            const storePrice = storePriceEl.textContent.trim();
            
            // Adresse du magasin
            const addressEl = card.querySelector('.text-secondary.small');
            const address = addressEl ? addressEl.textContent.trim() : '';
            
            // Lien Google Maps
            const mapsLinkEl = card.querySelector('a[href*="maps"]');
            const mapsUrl = mapsLinkEl ? mapsLinkEl.href : null;
            
            // Vérifier si c'est le meilleur prix
            const isBestPrice = card.querySelector('.badge.bg-success') !== null;
            
            stores.push({
                name: storeName,
                price: storePrice,
                address: address,
                mapsUrl: mapsUrl,
                isBestPrice: isBestPrice
            });
        });
        
        return {
            name: productName,
            image: productImage,
            minPrice: minPrice,
            maxPrice: maxPrice,
            avgPrice: avgPrice,
            economy: economyText,
            stores: stores.slice(0, 6) // Limiter à 6 magasins pour mobile
        };
        
    } catch (error) {
        console.error('Erreur extraction:', error);
        return null;
    }
}

// Format de la carte produit
function formatProductCard(product) {
    let statsHtml = '';
    if (product.minPrice || product.avgPrice || product.maxPrice) {
        statsHtml = `
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin: 16px 0; padding: 16px; background: rgba(255, 255, 255, 0.05); border-radius: 12px;">
                ${product.minPrice ? `<div style="text-align: center;"><div style="color: var(--accent-green); font-size: 12px; margin-bottom: 4px;">Prix Min</div><div style="font-weight: 700; color: var(--accent-green);">${product.minPrice}</div></div>` : ''}
                ${product.avgPrice ? `<div style="text-align: center;"><div style="color: var(--text-secondary); font-size: 12px; margin-bottom: 4px;">Moyenne</div><div style="font-weight: 600;">${product.avgPrice}</div></div>` : ''}
                ${product.maxPrice ? `<div style="text-align: center;"><div style="color: var(--accent-orange); font-size: 12px; margin-bottom: 4px;">Prix Max</div><div style="font-weight: 600; color: var(--accent-orange);">${product.maxPrice}</div></div>` : ''}
            </div>
        `;
    }
    
    const storesHtml = product.stores.map(store => `
        <div style="background: rgba(255, 255, 255, 0.05); border-radius: 8px; padding: 12px; margin-bottom: 8px; ${store.isBestPrice ? 'border: 1px solid var(--accent-green);' : ''}">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                <div style="flex: 1;">
                    <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 4px;">
                        ${store.name}
                        ${store.isBestPrice ? ' <span style="background: var(--accent-green); color: white; font-size: 10px; padding: 2px 6px; border-radius: 4px;">MEILLEUR</span>' : ''}
                    </div>
                    <div style="font-size: 12px; color: var(--text-secondary); line-height: 1.3;">
                        ${store.address}
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-weight: 700; font-size: 16px; color: ${store.isBestPrice ? 'var(--accent-green)' : 'var(--text-primary)'};">
                        ${store.price}
                    </div>
                </div>
            </div>
            ${store.mapsUrl ? `
                <a href="${store.mapsUrl}" target="_blank" style="display: inline-flex; align-items: center; gap: 4px; background: rgba(255, 255, 255, 0.1); color: var(--text-primary); text-decoration: none; font-size: 12px; padding: 6px 10px; border-radius: 6px; transition: var(--transition);">
                    🗺️ Voir sur la carte
                </a>
            ` : ''}
        </div>
    `).join('');
    
    return `
        <div class="product-card">
            <div class="product-header">
                ${product.image ? `<img src="${product.image}" alt="${product.name}" class="product-image">` : '<div class="product-image" style="background: rgba(255, 255, 255, 0.1); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 24px;">📦</div>'}
                <div class="product-info">
                    <div class="product-name">${product.name}</div>
                    ${product.minPrice ? `<div class="product-price">${product.minPrice}</div>` : ''}
                    ${product.economy ? `<div style="color: var(--accent-green); font-size: 14px; margin-top: 4px;">${product.economy}</div>` : ''}
                </div>
            </div>
            
            ${statsHtml}
            
            ${storesHtml ? `
                <div style="margin-top: 16px;">
                    <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 12px; font-size: 16px;">🏪 Magasins (${product.stores.length})</div>
                    ${storesHtml}
                </div>
            ` : ''}
        </div>
    `;
}

// Nettoyage à la fermeture
window.addEventListener('beforeunload', () => {
    stopScanner();
});
</script>

</body>
</html>
@endsection