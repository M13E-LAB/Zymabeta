<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "🔍 TEST LARAVEL REGISTRATION DEBUG<br><br>";

// Test de la connection à Laravel
$laravel_path = __DIR__ . '/../bootstrap/app.php';
echo "Laravel Path: " . $laravel_path . "<br>";

if (file_exists($laravel_path)) {
    echo "✅ Laravel bootstrap existe<br>";
    
    try {
        $app = require_once $laravel_path;
        echo "✅ Laravel app chargée<br>";
        
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        echo "✅ Kernel créé<br>";
        
        // Test de la base de données
        $pdo = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
        echo "✅ Base SQLite accessible<br>";
        
        // Test table users
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        $count = $stmt->fetchColumn();
        echo "✅ Table users: " . $count . " utilisateurs<br>";
        
        // Test table point_transactions
        $stmt = $pdo->query("SELECT COUNT(*) FROM point_transactions");
        $count = $stmt->fetchColumn();
        echo "✅ Table point_transactions: " . $count . " transactions<br>";
        
    } catch (Exception $e) {
        echo "❌ Erreur Laravel: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Laravel bootstrap introuvable<br>";
}

echo "<br>📋 DERNIERS LOGS LARAVEL:<br>";
$log_file = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($log_file)) {
    $logs = file_get_contents($log_file);
    $last_lines = array_slice(explode("\n", $logs), -20);
    echo "<pre>" . implode("\n", $last_lines) . "</pre>";
} else {
    echo "❌ Fichier de log introuvable<br>";
}
?> 