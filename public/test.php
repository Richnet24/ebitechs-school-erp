<?php
// Test ultra-simple pour Heroku
echo "<h1>🎉 PHP fonctionne sur Heroku !</h1>";
echo "<p>Timestamp: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>Version PHP: " . phpversion() . "</p>";
echo "<p>Répertoire courant: " . getcwd() . "</p>";

// Lister les fichiers dans le répertoire public
echo "<h2>Fichiers dans public/ :</h2>";
$files = scandir('.');
foreach($files as $file) {
    if($file != '.' && $file != '..') {
        echo "<p>- " . $file . "</p>";
    }
}

// Test de connexion à Laravel
echo "<h2>Test Laravel :</h2>";
if (file_exists('../vendor/autoload.php')) {
    echo "<p>✅ Composer autoload trouvé</p>";
    
    try {
        require_once '../vendor/autoload.php';
        echo "<p>✅ Autoload chargé</p>";
        
        if (file_exists('../bootstrap/app.php')) {
            echo "<p>✅ Bootstrap Laravel trouvé</p>";
            echo "<p><a href='/' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🏠 Aller à l'accueil</a></p>";
            echo "<p><a href='/admin/login' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔐 Aller à l'admin</a></p>";
        } else {
            echo "<p>❌ Bootstrap Laravel non trouvé</p>";
        }
        
    } catch (Exception $e) {
        echo "<p>❌ Erreur: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>❌ Composer autoload non trouvé</p>";
}
?>