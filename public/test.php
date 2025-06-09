<?php
echo "PHP Pure Test - ZYMA";
echo "<br>PHP Version: " . PHP_VERSION;
echo "<br>Server Time: " . date('Y-m-d H:i:s');
echo "<br>Status: Working without Laravel";

// Test base de données
try {
    $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost';
    $db = $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE') ?? 'test';
    $user = $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?? 'root';
    $pass = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?? '';
    
    echo "<br>DB Host: " . $host;
    echo "<br>DB Name: " . $db;
    
    if ($host && $host !== 'localhost') {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        echo "<br>✅ Database Connection: SUCCESS";
    } else {
        echo "<br>⚠️ Database Connection: SKIPPED (no config)";
    }
} catch (Exception $e) {
    echo "<br>❌ Database Connection: FAILED - " . $e->getMessage();
}

echo "<br><br>🎉 If you see this, PHP is working fine!";
?> 