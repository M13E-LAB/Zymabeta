<?php

use App\Http\Controllers\OpenFoodFactsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialFeedController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\BetaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Routes de diagnostic (gardées pour debug)
Route::get('/test', function () {
    return "ZYMA is working!";
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now(),
        'app' => 'ZYMA',
        'env' => 'production'
    ]);
});

Route::get('/db-test', function () {
    try {
        \DB::connection()->getPdo();
        return response()->json(['db_status' => 'Connected']);
    } catch (\Exception $e) {
        return response()->json(['db_status' => 'Failed', 'error' => $e->getMessage()]);
    }
});

// ROUTES ULTRA-BASIQUES - AUCUNE DÉPENDANCE
Route::get('/', function () {
    return '<!DOCTYPE html>
<html>
<head>
    <title>ZYMA Beta</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #1a1a2e; color: white; }
        .container { max-width: 600px; margin: 0 auto; background: #16213e; padding: 40px; border-radius: 15px; }
        h1 { color: #00d4aa; margin-bottom: 20px; font-size: 3em; }
        .status { background: #28a745; color: white; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .links a { display: inline-block; margin: 10px; padding: 15px 25px; background: #007bff; color: white; text-decoration: none; border-radius: 8px; }
        .beta-info { background: #0d6efd; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .debug { background: #6c757d; padding: 15px; border-radius: 8px; margin: 10px 0; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 ZYMA BETA</h1>
        <div class="status">✅ Application en ligne !</div>
        
        <div class="beta-info">
            <h2>🔐 Accès Beta Privé</h2>
            <p>Version de test déployée avec succès</p>
        </div>
        
        <div class="debug">
            <strong>Debug Info:</strong><br>
            PHP: ' . PHP_VERSION . '<br>
            Timestamp: ' . date('Y-m-d H:i:s') . '<br>
            Status: ONLINE
        </div>
        
        <div class="links">
            <a href="/simple">Test Simple</a>
            <a href="/env-check">Check ENV</a>
        </div>
        
        <p style="margin-top: 30px; color: #6c757d;">
            ZYMA v1.0 - ' . date('d/m/Y H:i') . '
        </p>
    </div>
</body>
</html>';
});

// Test ultra-simple
Route::get('/simple', function () {
    return 'ZYMA Simple Test - OK at ' . date('Y-m-d H:i:s');
});

// Test des variables d'environnement
Route::get('/env-check', function () {
    $html = '<h1>Environment Check</h1>';
    $html .= '<p>APP_ENV: ' . (env('APP_ENV', 'not set')) . '</p>';
    $html .= '<p>APP_KEY: ' . (env('APP_KEY') ? 'SET (' . substr(env('APP_KEY'), 0, 20) . '...)' : 'NOT SET') . '</p>';
    $html .= '<p>DB_HOST: ' . (env('DB_HOST', 'not set')) . '</p>';
    $html .= '<p>DB_DATABASE: ' . (env('DB_DATABASE', 'not set')) . '</p>';
    
    return $html;
});

// Test de base SANS AUTH ni middleware
Route::get('/test-basic', function () {
    return response()->json([
        'status' => 'OK',
        'app' => 'ZYMA',
        'php' => PHP_VERSION,
        'time' => now()->toDateTimeString()
    ]);
});

// Routes Beta SÉCURISÉES (commentées temporairement)
/*
Route::get('/', [BetaController::class, 'welcome'])->name('beta.welcome');
Route::post('/beta/verify', [BetaController::class, 'verifyCode'])->name('beta.verify');
*/

// Routes d'authentification
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    // Routes admin beta (protégées)
    Route::get('/beta/dashboard', [BetaController::class, 'dashboard'])->name('beta.dashboard');
    Route::get('/beta/codes', [BetaController::class, 'listCodes'])->name('beta.codes');
    Route::post('/beta/generate', [BetaController::class, 'generateCodes'])->name('beta.generate');

    // Routes principales de l'application
    Route::get('/products', [OpenFoodFactsController::class, 'index'])->name('products.search');
    Route::post('/fetch', [OpenFoodFactsController::class, 'fetch'])->name('products.fetch');
    Route::get('/products/search', [OpenFoodFactsController::class, 'searchByName'])->name('products.searchByName');
    Route::get('/api/products/search', [OpenFoodFactsController::class, 'apiSearchByName'])->name('api.products.search');
    Route::get('/products/{id}', [OpenFoodFactsController::class, 'show'])->name('products.show');
});
