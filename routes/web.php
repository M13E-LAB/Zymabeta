<?php

use App\Http\Controllers\OpenFoodFactsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialFeedController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\BetaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route ultra-simple pour test
Route::get('/test', function () {
    return "ZYMA is working!";
});

// Route de diagnostic simple
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now(),
        'app' => 'ZYMA',
        'env' => 'production'
    ]);
});

// Test de connexion DB
Route::get('/db-test', function () {
    try {
        \DB::connection()->getPdo();
        return response()->json(['db_status' => 'Connected']);
    } catch (\Exception $e) {
        return response()->json(['db_status' => 'Failed', 'error' => $e->getMessage()]);
    }
});

// Route principale ultra-simple - AUCUN CONTROLLER
Route::get('/', function () {
    return '<!DOCTYPE html>
<html>
<head>
    <title>ZYMA - Application Laravel</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f8f9fa; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #28a745; margin-bottom: 20px; }
        .status { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .links { margin-top: 30px; }
        .links a { display: inline-block; margin: 10px; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .links a:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 ZYMA Application</h1>
        <div class="status">
            ✅ Laravel Application Successfully Deployed!
        </div>
        <p><strong>Votre application ZYMA est maintenant en ligne et fonctionne parfaitement.</strong></p>
        <p>Déploiement réussi sur Railway avec PHP ' . PHP_VERSION . ' et Laravel Framework.</p>
        <div class="links">
            <a href="/health">Health Check</a>
            <a href="/db-test">Database Test</a>
            <a href="/test">Simple Test</a>
        </div>
        <p style="margin-top: 30px; color: #6c757d; font-size: 14px;">
            Déployé le ' . date('d/m/Y à H:i:s') . ' - Status: ACTIVE
        </p>
    </div>
</body>
</html>';
});

// Toutes les autres routes commentées temporairement
/*
Route::get('/', [OpenFoodFactsController::class, 'index'])->name('products.search');

Route::post('/fetch', [OpenFoodFactsController::class, 'fetch'])->name('products.fetch');
Route::get('/products/search', [OpenFoodFactsController::class, 'searchByName'])->name('products.searchByName');
Route::get('/api/products/search', [OpenFoodFactsController::class, 'apiSearchByName'])->name('api.products.search');
Route::get('/products/{id}', [OpenFoodFactsController::class, 'show'])->name('products.show');

// Nouvelle route de statistiques
Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
Route::get('/statistics/city/{city}', [StatisticsController::class, 'cityDetail'])->name('statistics.city');
Route::get('/api/statistics', [StatisticsController::class, 'apiStats'])->name('api.statistics');

// Routes Beta (avant les autres routes)
Route::get('/', [BetaController::class, 'welcome'])->name('beta.welcome');
Route::post('/beta/verify', [BetaController::class, 'verifyCode'])->name('beta.verify');

// Profil utilisateur et Feed Social
Route::middleware(['auth'])->group(function () {
    // Routes du profil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/posts', [ProfileController::class, 'posts'])->name('profile.posts');
    Route::get('/profile/points', [ProfileController::class, 'points'])->name('profile.points');
    Route::get('/profile/badges', [ProfileController::class, 'badges'])->name('profile.badges');
    
    // Routes d'onboarding
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
    
    // Routes du feed social (style BeReal de la nourriture)
    Route::get('/social', [SocialFeedController::class, 'index'])->name('social.feed');
    Route::get('/social/create', [SocialFeedController::class, 'create'])->name('social.create');
    Route::post('/social', [SocialFeedController::class, 'store'])->name('social.store');
    Route::get('/social/{post}', [SocialFeedController::class, 'show'])->name('social.show');
    Route::get('/social/{post}/edit', [SocialFeedController::class, 'edit'])->name('social.edit');
    Route::patch('/social/{post}', [SocialFeedController::class, 'update'])->name('social.update');
    Route::delete('/social/{post}', [SocialFeedController::class, 'destroy'])->name('social.destroy');
    Route::post('/social/{post}/like', [SocialFeedController::class, 'like'])->name('social.like');
    Route::post('/social/{post}/comment', [SocialFeedController::class, 'comment'])->name('social.comment');
    // Les repas sont automatiquement analysés par l'IA lors de l'upload
    // Routes pour les ligues
    Route::get('/leagues', [App\Http\Controllers\LeagueController::class, 'index'])->name('leagues.index');
    Route::get('/leagues/create', [App\Http\Controllers\LeagueController::class, 'create'])->name('leagues.create');
    Route::post('/leagues', [App\Http\Controllers\LeagueController::class, 'store'])->name('leagues.store');
    Route::get('/leagues/meal-upload', [App\Http\Controllers\LeagueController::class, 'mealUpload'])->name('leagues.meal.upload.general');
    Route::post('/leagues/meal-store', [App\Http\Controllers\LeagueController::class, 'mealStore'])->name('leagues.meal.store');
    Route::get('/leagues/{slug}', [App\Http\Controllers\LeagueController::class, 'show'])->name('leagues.show');
    Route::post('/leagues/join', [App\Http\Controllers\LeagueController::class, 'join'])->name('leagues.join');
    Route::delete('/leagues/{slug}/leave', [App\Http\Controllers\LeagueController::class, 'leave'])->name('leagues.leave');
    Route::patch('/leagues/{slug}/members/{userId}', [App\Http\Controllers\LeagueController::class, 'updateMemberRole'])->name('leagues.updateMemberRole');
    Route::delete('/leagues/{slug}/members/{userId}', [App\Http\Controllers\LeagueController::class, 'removeMember'])->name('leagues.removeMember');
    Route::get('/leaderboard', [App\Http\Controllers\LeagueController::class, 'globalLeaderboard'])->name('leaderboard.global');
    Route::get('/leagues/{slug}/meal-upload', [App\Http\Controllers\LeagueController::class, 'mealUpload'])->name('leagues.meal.upload');

    // Routes admin beta (protégées)
    Route::get('/beta/dashboard', [BetaController::class, 'dashboard'])->name('beta.dashboard');
    Route::get('/beta/codes', [BetaController::class, 'listCodes'])->name('beta.codes');
    Route::post('/beta/generate', [BetaController::class, 'generateCodes'])->name('beta.generate');
});

// Routes d'authentification (login, register, password reset, etc.)
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
*/
