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

// Routes Beta ACTIVÉES
Route::get('/', [BetaController::class, 'welcome'])->name('beta.welcome');
Route::post('/beta/verify', [BetaController::class, 'verifyCode'])->name('beta.verify');

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
