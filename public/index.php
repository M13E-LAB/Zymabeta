<?php
// Test ultra-simple pour voir si c'est Laravel qui pose problème

echo "<!DOCTYPE html>";
echo "<html><head><title>ZYMA Test</title></head><body>";
echo "<h1>🔧 ZYMA Debug Mode</h1>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>Server Time:</strong> " . date('Y-m-d H:i:s') . "</p>";

// Test des variables d'environnement
echo "<h2>Environment Variables:</h2>";
echo "<p>APP_ENV: " . ($_ENV['APP_ENV'] ?? getenv('APP_ENV') ?? 'not set') . "</p>";
echo "<p>APP_KEY: " . (($_ENV['APP_KEY'] ?? getenv('APP_KEY')) ? 'SET' : 'NOT SET') . "</p>";
echo "<p>DB_HOST: " . ($_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'not set') . "</p>";

// Test de base de données
echo "<h2>Database Test:</h2>";
try {
    $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
    $db = $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE');
    $user = $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME');
    $pass = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD');
    
    if ($host && $db) {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        echo "<p style='color: green;'>✅ Database Connection: SUCCESS</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Database Connection: No config found</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database Connection: " . $e->getMessage() . "</p>";
}

echo "<h2>Laravel Test:</h2>";
echo "<p>Trying to load Laravel...</p>";

try {
    // Essayer de charger Laravel
    require_once __DIR__.'/../vendor/autoload.php';
    echo "<p style='color: green;'>✅ Autoload: SUCCESS</p>";
    
    $app = require_once __DIR__.'/../bootstrap/app.php';
    echo "<p style='color: green;'>✅ Bootstrap: SUCCESS</p>";
    
    echo "<p style='color: green;'>🎉 Laravel loaded successfully! The issue is in routes or controllers.</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Laravel Error: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . " Line: " . $e->getLine() . "</p>";
}

echo "<hr>";
echo "<p><a href='/test.php'>Test PHP Pure</a></p>";
echo "</body></html>";
?>
