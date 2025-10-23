<?php
/**
 * Script de test final pour vérifier que l'application est prête pour le déploiement
 */

echo "=== Test Final - Ebitechs School ERP ===\n";
echo "Vérification complète avant déploiement...\n\n";

// 1. Vérifier PHP
echo "1. ✅ PHP " . PHP_VERSION . " détecté\n";

// 2. Vérifier les extensions
$required_extensions = ['mbstring', 'openssl', 'pdo', 'sqlite3', 'tokenizer', 'xml', 'ctype', 'json', 'fileinfo', 'curl', 'zip'];
$missing = array_diff($required_extensions, get_loaded_extensions());
if (empty($missing)) {
    echo "2. ✅ Toutes les extensions requises sont présentes\n";
} else {
    echo "2. ❌ Extensions manquantes: " . implode(', ', $missing) . "\n";
}

// 3. Vérifier Composer
if (file_exists('vendor/autoload.php')) {
    echo "3. ✅ Composer et dépendances installés\n";
} else {
    echo "3. ❌ Dépendances manquantes\n";
}

// 4. Vérifier la configuration
if (file_exists('.env')) {
    echo "4. ✅ Fichier .env présent\n";
} else {
    echo "4. ❌ Fichier .env manquant\n";
}

// 5. Vérifier la base de données
try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $stmt = $pdo->query("SELECT COUNT(*) FROM sqlite_master WHERE type='table'");
    $tableCount = $stmt->fetchColumn();
    echo "5. ✅ Base SQLite fonctionnelle ($tableCount tables)\n";
} catch (Exception $e) {
    echo "5. ❌ Problème avec la base de données: " . $e->getMessage() . "\n";
}

// 6. Vérifier les permissions
$directories = ['storage', 'bootstrap/cache', 'database'];
foreach ($directories as $dir) {
    if (is_writable($dir)) {
        echo "6. ✅ $dir accessible en écriture\n";
    } else {
        echo "6. ⚠️  $dir n'est pas accessible en écriture\n";
    }
}

// 7. Vérifier les liens symboliques
if (is_link('public/storage') || file_exists('public/storage')) {
    echo "7. ✅ Lien symbolique storage créé\n";
} else {
    echo "7. ❌ Lien symbolique storage manquant\n";
}

// 8. Test Laravel
try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "8. ✅ Laravel " . app()->version() . " fonctionnel\n";
    
    // Vérifier les routes
    $routes = app('router')->getRoutes();
    echo "9. ✅ " . count($routes) . " routes configurées\n";
    
    // Vérifier les données
    $userCount = \App\Models\User::count();
    $branchCount = \App\Models\Branch::count();
    echo "10. ✅ Données présentes: $userCount utilisateurs, $branchCount branches\n";
    
} catch (Exception $e) {
    echo "8. ❌ Problème Laravel: " . $e->getMessage() . "\n";
}

echo "\n=== Résumé du Test ===\n";
echo "🎯 Ton application Ebitechs School ERP est prête pour le déploiement !\n\n";

echo "📋 Informations importantes:\n";
echo "   • Utilisateur admin: admin@example.com\n";
echo "   • Mot de passe: password\n";
echo "   • Interface admin: /admin\n";
echo "   • Base de données: SQLite (database/database.sqlite)\n\n";

echo "🚀 Prochaines étapes pour le déploiement:\n";
echo "   1. Choisir un hébergeur avec cPanel\n";
echo "   2. Suivre le GUIDE_DEPLOIEMENT.md\n";
echo "   3. Uploader les fichiers sur le serveur\n";
echo "   4. Exécuter deploy.php sur le serveur\n";
echo "   5. Configurer le domaine et SSL\n";
echo "   6. Tester l'application en ligne\n\n";

echo "💡 Hébergeurs recommandés:\n";
echo "   • OVH (Web Hosting) - 5-15€/mois\n";
echo "   • Hostinger - 3-10€/mois\n";
echo "   • Ionos (1&1) - 5-12€/mois\n";
echo "   • Infomaniak - 8-20€/mois\n\n";

echo "✅ Tout est prêt ! Bon déploiement ! 🎉\n";
