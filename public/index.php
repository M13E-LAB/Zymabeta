<?php
// BYPASS COMPLET DE LARAVEL - PHP PUR
echo '<!DOCTYPE html>
<html>
<head>
    <title>ZYMA Beta - PHP Pure</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #1a1a2e; color: white; }
        .container { max-width: 700px; margin: 0 auto; background: #16213e; padding: 40px; border-radius: 15px; }
        h1 { color: #00d4aa; margin-bottom: 20px; font-size: 3em; }
        .status { background: #28a745; color: white; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .error { background: #dc3545; color: white; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .info { background: #17a2b8; color: white; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .debug { background: #6c757d; padding: 15px; border-radius: 8px; margin: 10px 0; font-size: 12px; text-align: left; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 ZYMA BETA</h1>
        <div class="status">✅ PHP Direct Access Working!</div>
        
        <div class="info">
            <h3>🔧 System Status</h3>
            <p><strong>PHP Version:</strong> ' . PHP_VERSION . '</p>
            <p><strong>Server Time:</strong> ' . date('Y-m-d H:i:s') . '</p>
            <p><strong>Memory Limit:</strong> ' . ini_get('memory_limit') . '</p>
        </div>';

// Test des variables d'environnement
echo '<div class="debug">
            <strong>🌍 Environment Variables:</strong><br>';

$env_vars = [
    'APP_ENV' => $_ENV['APP_ENV'] ?? getenv('APP_ENV') ?? 'not set',
    'APP_KEY' => ($_ENV['APP_KEY'] ?? getenv('APP_KEY')) ? 'SET (length: ' . strlen($_ENV['APP_KEY'] ?? getenv('APP_KEY')) . ')' : 'NOT SET',
    'DB_HOST' => $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'not set',
    'DB_DATABASE' => $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE') ?? 'not set',
    'RAILWAY_PUBLIC_DOMAIN' => $_ENV['RAILWAY_PUBLIC_DOMAIN'] ?? getenv('RAILWAY_PUBLIC_DOMAIN') ?? 'not set'
];

foreach ($env_vars as $key => $value) {
    echo "$key: $value<br>";
}

echo '</div>';

// Test de connexion DB
echo '<div class="debug">
            <strong>🗄️ Database Test:</strong><br>';

try {
    $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
    $db = $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE');
    $user = $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME');
    $pass = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD');
    
    if ($host && $db && $user && $pass) {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        echo '<span style="color: #28a745;">✅ Database Connection: SUCCESS</span><br>';
        echo "Host: $host<br>";
        echo "Database: $db<br>";
    } else {
        echo '<span style="color: #ffc107;">⚠️ Database Connection: Missing ENV vars</span><br>';
    }
} catch (Exception $e) {
    echo '<span style="color: #dc3545;">❌ Database Connection: ' . $e->getMessage() . '</span><br>';
}

echo '</div>';

// Test Laravel bootstrap
echo '<div class="debug">
            <strong>🔧 Laravel Bootstrap Test:</strong><br>';

try {
    if (file_exists(__DIR__.'/../vendor/autoload.php')) {
        echo '✅ Composer autoload exists<br>';
        require_once __DIR__.'/../vendor/autoload.php';
        echo '✅ Composer autoload loaded<br>';
        
        if (file_exists(__DIR__.'/../bootstrap/app.php')) {
            echo '✅ Bootstrap file exists<br>';
            
            // Test du bootstrap SANS faire de requête
            $app = require_once __DIR__.'/../bootstrap/app.php';
            echo '✅ Bootstrap loaded successfully<br>';
            echo '<span style="color: #28a745;">🎉 Laravel CAN be loaded - Issue is in routes/middleware!</span><br>';
        } else {
            echo '<span style="color: #dc3545;">❌ Bootstrap file missing</span><br>';
        }
    } else {
        echo '<span style="color: #dc3545;">❌ Composer autoload missing</span><br>';
    }
} catch (Exception $e) {
    echo '<span style="color: #dc3545;">❌ Laravel Error: ' . $e->getMessage() . '</span><br>';
    echo 'File: ' . $e->getFile() . ' Line: ' . $e->getLine() . '<br>';
}

echo '</div>';

echo '<div class="info">
            <h3>📱 Application Beta</h3>
            <p>Version de diagnostic déployée</p>
            <p>Si vous voyez cette page, PHP fonctionne parfaitement.</p>
            <p>Le problème est dans la configuration Laravel.</p>
        </div>
        
        <p style="margin-top: 30px; color: #6c757d;">
            ZYMA Diagnostic v1.0 - ' . date('d/m/Y H:i:s') . '
        </p>
    </div>
</body>
</html>';
?> 