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

// Route principale temporaire - TESTE SIMPLE
Route::get('/', function () {
    return '<!DOCTYPE html>
<html>
<head>
    <title>ZYMA Beta</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #1a1a2e; color: white; }
        .container { max-width: 600px; margin: 0 auto; background: #16213e; padding: 40px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
        h1 { color: #00d4aa; margin-bottom: 20px; font-size: 3em; }
        .status { background: #28a745; color: white; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .links { margin-top: 30px; }
        .links a { display: inline-block; margin: 10px; padding: 15px 25px; background: #007bff; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; }
        .links a:hover { background: #0056b3; transform: translateY(-2px); transition: all 0.3s; }
        .beta-info { background: #0d6efd; padding: 20px; border-radius: 8px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 ZYMA</h1>
        <div class="status">
            ✅ Application Beta Déployée avec Succès !
        </div>
        
        <div class="beta-info">
            <h2>🔐 Accès Beta</h2>
            <p>Cette application est en phase beta privée.</p>
            <p>Un code d\'invitation est requis pour accéder à l\'application complète.</p>
        </div>
        
        <div class="links">
            <a href="/test">Test Simple</a>
            <a href="/health">Health Check</a>
            <a href="/db-test">Database Status</a>
        </div>
        
        <div class="links">
            <a href="/beta/dashboard" style="background: #28a745;">Dashboard Beta</a>
            <a href="/register" style="background: #dc3545;">Inscription</a>
            <a href="/login" style="background: #6c757d;">Connexion</a>
        </div>
        
        <p style="margin-top: 30px; color: #6c757d; font-size: 14px;">
            ZYMA Beta v1.0 - Déployé le ' . date('d/m/Y à H:i:s') . '
        </p>
    </div>
</body>
</html>';
});

// Test route pour beta controller
Route::get('/beta-test', function () {
    try {
        $controller = new \App\Http\Controllers\BetaController();
        return "BetaController instantiated successfully!";
    } catch (Exception $e) {
        return "BetaController Error: " . $e->getMessage();
    }
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
