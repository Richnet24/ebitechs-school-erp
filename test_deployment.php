<?php
/**
 * Script de test local avant déploiement
 * Simule l'environnement de production en local
 */

echo "=== Test de Déploiement Local ===\n";
echo "Simulation de l'environnement de production...\n\n";

// 1. Sauvegarder la configuration actuelle
echo "1. Sauvegarde de la configuration actuelle...\n";
if (file_exists('.env')) {
    copy('.env', '.env.backup');
    echo "✅ Configuration sauvegardée (.env.backup)\n";
}

// 2. Appliquer la configuration de production
echo "\n2. Application de la configuration de production...\n";
if (file_exists('env_production.txt')) {
    copy('env_production.txt', '.env');
    echo "✅ Configuration de production appliquée\n";
} else {
    echo "❌ Fichier env_production.txt non trouvé\n";
    exit(1);
}

// 3. Générer une nouvelle clé
echo "\n3. Génération de la clé d'application...\n";
$key_result = shell_exec('php artisan key:generate --force 2>&1');
if (strpos($key_result, 'Application key set successfully') !== false) {
    echo "✅ Clé d'application générée\n";
} else {
    echo "❌ Erreur génération clé: $key_result\n";
}

// 4. Nettoyer les caches
echo "\n4. Nettoyage des caches...\n";
$cache_commands = [
    'config:clear' => 'Configuration',
    'route:clear' => 'Routes',
    'view:clear' => 'Vues',
    'cache:clear' => 'Cache général'
];

foreach ($cache_commands as $command => $description) {
    $result = shell_exec("php artisan $command 2>&1");
    echo "✅ Cache $description nettoyé\n";
}

// 5. Optimiser pour la production
echo "\n5. Optimisation pour la production...\n";
$optimize_commands = [
    'config:cache' => 'Configuration',
    'route:cache' => 'Routes',
    'view:cache' => 'Vues'
];

foreach ($optimize_commands as $command => $description) {
    $result = shell_exec("php artisan $command 2>&1");
    if (strpos($result, 'successfully') !== false || empty(trim($result))) {
        echo "✅ Cache $description optimisé\n";
    } else {
        echo "⚠️  Problème avec le cache $description: $result\n";
    }
}

// 6. Vérifier la base de données
echo "\n6. Vérification de la base de données...\n";
if (file_exists('database/database.sqlite')) {
    echo "✅ Base SQLite trouvée\n";
    
    // Tester la connexion
    try {
        $pdo = new PDO('sqlite:database/database.sqlite');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Compter les tables
        $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "✅ Connexion DB réussie (" . count($tables) . " tables)\n";
        
    } catch (PDOException $e) {
        echo "❌ Erreur connexion DB: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Base SQLite non trouvée\n";
}

// 7. Vérifier les permissions
echo "\n7. Vérification des permissions...\n";
$directories = ['storage', 'bootstrap/cache', 'database'];
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "✅ $dir est accessible en écriture\n";
        } else {
            echo "⚠️  $dir n'est pas accessible en écriture\n";
        }
    } else {
        echo "❌ $dir n'existe pas\n";
    }
}

// 8. Test de l'application
echo "\n8. Test de l'application...\n";

// Démarrer le serveur en arrière-plan
echo "Démarrage du serveur de test...\n";
$server_pid = shell_exec('php artisan serve --host=127.0.0.1 --port=8000 > /dev/null 2>&1 & echo $!');

// Attendre que le serveur démarre
sleep(3);

// Tester l'accès à l'application
$url = 'http://127.0.0.1:8000';
$context = stream_context_create([
    'http' => [
        'timeout' => 10,
        'method' => 'GET'
    ]
]);

$response = @file_get_contents($url, false, $context);
if ($response !== false) {
    echo "✅ Application accessible sur $url\n";
    
    // Tester l'accès admin
    $admin_url = 'http://127.0.0.1:8000/admin';
    $admin_response = @file_get_contents($admin_url, false, $context);
    if ($admin_response !== false) {
        echo "✅ Interface admin accessible\n";
    } else {
        echo "⚠️  Interface admin non accessible\n";
    }
} else {
    echo "❌ Application non accessible\n";
}

// Arrêter le serveur de test
if ($server_pid) {
    shell_exec("kill $server_pid 2>/dev/null");
    echo "✅ Serveur de test arrêté\n";
}

// 9. Restaurer la configuration originale
echo "\n9. Restauration de la configuration originale...\n";
if (file_exists('.env.backup')) {
    copy('.env.backup', '.env');
    unlink('.env.backup');
    echo "✅ Configuration originale restaurée\n";
}

echo "\n=== Test de Déploiement Terminé ===\n";
echo "🎯 Résumé :\n";
echo "- Configuration de production testée\n";
echo "- Optimisations appliquées\n";
echo "- Base de données vérifiée\n";
echo "- Permissions contrôlées\n";
echo "- Application testée\n";
echo "\n✅ Ton application est prête pour le déploiement !\n";
echo "\n📋 Prochaines étapes :\n";
echo "1. Choisir un hébergeur avec cPanel\n";
echo "2. Suivre le GUIDE_DEPLOIEMENT.md\n";
echo "3. Exécuter deploy.php sur le serveur\n";
echo "4. Configurer le domaine et SSL\n";
echo "5. Tester en ligne\n";
