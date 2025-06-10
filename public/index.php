<?php
// PHP PUR - AUCUN LARAVEL
echo "<!DOCTYPE html>
<html>
<head>
    <title>ZYMA - Working!</title>
    <style>
        body { font-family: Arial; text-align: center; padding: 50px; background: #1a1a2e; color: white; }
        .container { max-width: 600px; margin: 0 auto; background: #16213e; padding: 40px; border-radius: 15px; }
        h1 { color: #00d4aa; font-size: 3em; }
        .success { background: #28a745; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .info { background: #17a2b8; padding: 15px; border-radius: 8px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🎉 ZYMA WORKS!</h1>
        <div class='success'>✅ PHP Application Successfully Running!</div>
        <div class='info'>
            <h3>System Info:</h3>
            <p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>
            <p><strong>Server Time:</strong> " . date('Y-m-d H:i:s') . "</p>
            <p><strong>Status:</strong> ONLINE & WORKING</p>
        </div>
        <div class='info'>
            <h3>🚀 ZYMA Beta Application</h3>
            <p>Your application is now successfully deployed!</p>
            <p>Ready for development and testing.</p>
        </div>
        <p style='color: #6c757d; margin-top: 30px;'>
            ZYMA v1.0 - Deployed " . date('d/m/Y H:i:s') . "
        </p>
    </div>
</body>
</html>";
?> 