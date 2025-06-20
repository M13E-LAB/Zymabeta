<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🧪 TEST REGISTRATION SIMPLE</h1>";

if ($_POST) {
    echo "<h2>📋 DONNÉES REÇUES :</h2>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
    
    // Test connexion base de données
    try {
        $pdo = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
        echo "<p>✅ Connexion SQLite OK</p>";
        
        // Test insertion utilisateur simple
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([
            $_POST['name'] ?? 'Test User',
            $_POST['email'] ?? 'test@test.com',
            password_hash($_POST['password'] ?? 'password123', PASSWORD_DEFAULT),
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            echo "<p>✅ UTILISATEUR CRÉÉ AVEC SUCCÈS !</p>";
            $userId = $pdo->lastInsertId();
            echo "<p>ID utilisateur : " . $userId . "</p>";
        } else {
            echo "<p>❌ Erreur lors de la création</p>";
        }
        
    } catch (Exception $e) {
        echo "<p>❌ Erreur DB: " . $e->getMessage() . "</p>";
    }
} else {
    echo '<form method="POST">
        <p><input type="text" name="name" placeholder="Nom" required></p>
        <p><input type="email" name="email" placeholder="Email" required></p>
        <p><input type="password" name="password" placeholder="Mot de passe" required></p>
        <p><button type="submit">TESTER CRÉATION</button></p>
    </form>';
}
?> 