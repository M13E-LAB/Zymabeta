<?php

use App\Http\Controllers\OpenFoodFactsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialFeedController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\BetaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Page d'accueil principale - Landing page ZYMA
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Routes de test rapide
Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'ZYMA Beta API working!',
        'timestamp' => now()
    ]);
});

// Route publique temporaire pour tester l'interface moderne
Route::get('/demo-interface', function () {
    return view('products.search');
})->name('demo.interface');

Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'app' => 'ZYMA Beta',
        'version' => '1.0.0'
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

// Dashboard après connexion
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    
    // Profil utilisateur
    Route::get('/profile', function () {
        return view('profile', ['user' => auth()->user()]);
    })->name('profile.show');
    
    Route::get('/profile/edit', function () {
        return view('profile.edit', ['user' => auth()->user()]);
    })->name('profile.edit');
    
    Route::get('/profile/posts', function () {
        return redirect()->route('profile.show');
    })->name('profile.posts');
    
    Route::get('/profile/points', function () {
        return redirect()->route('profile.show');
    })->name('profile.points');
    
    Route::get('/profile/badges', function () {
        return redirect()->route('profile.show');
    })->name('profile.badges');
    
    Route::get('/leaderboard', function () {
        return redirect()->route('leagues.index');
    })->name('leaderboard.global');
    
    // Feed social - routes complètes avec logique BeReal
    Route::get('/social', [SocialFeedController::class, 'index'])->name('social.feed');
    Route::get('/social/create', [SocialFeedController::class, 'create'])->name('social.create');
    Route::post('/social/store', [SocialFeedController::class, 'store'])->name('social.store');
    Route::get('/social/{post}', [SocialFeedController::class, 'show'])->name('social.show');
    Route::post('/social/{post}/like', [SocialFeedController::class, 'like'])->name('social.like');
    Route::post('/social/{post}/comment', [SocialFeedController::class, 'comment'])->name('social.comment');
    
    Route::get('/feed', function () {
        return redirect()->route('social.index');
    })->name('feed');
    
    // Ligues - route avec données de démo
    Route::get('/leagues', function () {
        // Simuler une ligue pour la démo
        $league = (object) [
            'name' => 'Les Smimos',
            'slug' => 'les-smimos',
            'is_private' => true,
            'creator' => (object) ['name' => 'Anouar Essakhi'],
            'description' => 'Ligue privée créée par Anouar Essakhi',
            'created_by' => auth()->id()
        ];
        
        $weeklyLeaderboard = collect([
            (object) [
                'id' => auth()->id(),
                'name' => 'Anouar Essakhi',
                'avatar' => null,
                'pivot' => (object) [
                    'position' => 1,
                    'weekly_score' => 18,
                    'last_score_update' => now()->subDays(2)
                ]
            ]
        ]);
        
        $monthlyLeaderboard = $weeklyLeaderboard;
        $overallLeaderboard = $weeklyLeaderboard;
        $isMember = true;
        
        return view('leagues.show', compact('league', 'weeklyLeaderboard', 'monthlyLeaderboard', 'overallLeaderboard', 'isMember'));
    })->name('leagues.index');
    
    // Recherche produits
    Route::get('/products', [OpenFoodFactsController::class, 'index'])->name('products.search');
    
    // Statistiques
    Route::get('/stats', function () {
        return view('stats');
    })->name('stats');

    // Routes admin beta (protégées)
    Route::get('/beta/dashboard', [BetaController::class, 'dashboard'])->name('beta.dashboard');
    Route::get('/beta/codes', [BetaController::class, 'listCodes'])->name('beta.codes');
    Route::post('/beta/generate', [BetaController::class, 'generateCodes'])->name('beta.generate');

    // Routes principales de l'application
    Route::post('/fetch', [OpenFoodFactsController::class, 'fetch'])->name('products.fetch');
    Route::get('/products/search', [OpenFoodFactsController::class, 'searchByName'])->name('products.searchByName');
    Route::get('/api/products/search', [OpenFoodFactsController::class, 'apiSearchByName'])->name('api.products.search');
    Route::get('/products/{id}', [OpenFoodFactsController::class, 'show'])->name('products.show');
});

// Routes Beta (pour lancement beta)
Route::get('/beta', [App\Http\Controllers\BetaController::class, 'welcome'])->name('beta.welcome');
Route::post('/beta/verify', [App\Http\Controllers\BetaController::class, 'verifyCode'])->name('beta.verify');
Route::get('/beta/dashboard', [App\Http\Controllers\BetaController::class, 'dashboard'])->name('beta.dashboard')->middleware('auth');
Route::get('/beta/codes', [App\Http\Controllers\BetaController::class, 'listCodes'])->name('beta.codes')->middleware('auth');
Route::post('/beta/generate', [App\Http\Controllers\BetaController::class, 'generateCodes'])->name('beta.generate')->middleware('auth');
