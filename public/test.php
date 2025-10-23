<?php
// Test simple pour vérifier que PHP fonctionne sur Heroku
echo "<h1>🎉 PHP fonctionne sur Heroku !</h1>";
echo "<p>Timestamp: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>Version PHP: " . phpversion() . "</p>";

// Test de connexion à Laravel
if (file_exists('vendor/autoload.php')) {
    echo "<p>✅ Composer autoload trouvé</p>";
    
    try {
        require_once 'vendor/autoload.php';
        $app = require_once 'bootstrap/app.php';
        echo "<p>✅ Laravel bootstrap réussi</p>";
        
        // Test de la route
        echo "<p><a href='/admin/login'>🔐 Aller à l'admin</a></p>";
        
    } catch (Exception $e) {
        echo "<p>❌ Erreur Laravel: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>❌ Composer autoload non trouvé</p>";
}
?>
