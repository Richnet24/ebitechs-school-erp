<?php
/**
 * Script de déploiement pour hébergement mutualisé cPanel
 * À exécuter sur le serveur après upload des fichiers
 */

echo "=== Déploiement Ebitechs School ERP ===\n";

// Vérifications préliminaires
echo "1. Vérification des prérequis...\n";

// Vérifier PHP version
if (version_compare(PHP_VERSION, '8.2.0', '<')) {
    die("❌ PHP 8.2+ requis. Version actuelle: " . PHP_VERSION . "\n");
}
echo "✅ PHP " . PHP_VERSION . " détecté\n";

// Vérifier les extensions nécessaires
$required_extensions = ['mbstring', 'openssl', 'pdo', 'sqlite3', 'tokenizer', 'xml', 'ctype', 'json', 'fileinfo', 'curl', 'zip'];
$missing_extensions = [];

foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $missing_extensions[] = $ext;
    }
}

if (!empty($missing_extensions)) {
    die("❌ Extensions manquantes: " . implode(', ', $missing_extensions) . "\n");
}
echo "✅ Toutes les extensions requises sont présentes\n";

// Vérifier Composer
if (!file_exists('composer.phar') && !shell_exec('which composer')) {
    echo "⚠️  Composer non détecté. Installation...\n";
    $composer_install = shell_exec('curl -sS https://getcomposer.org/installer | php');
    if (!$composer_install) {
        die("❌ Impossible d'installer Composer\n");
    }
    echo "✅ Composer installé\n";
}

echo "\n2. Installation des dépendances...\n";
$composer_cmd = file_exists('composer.phar') ? 'php composer.phar' : 'composer';
$install_result = shell_exec("$composer_cmd install --no-dev --optimize-autoloader --no-interaction 2>&1");

if (strpos($install_result, 'error') !== false || strpos($install_result, 'failed') !== false) {
    echo "❌ Erreur lors de l'installation des dépendances:\n$install_result\n";
    exit(1);
}
echo "✅ Dépendances installées\n";

echo "\n3. Configuration de l'application...\n";

// Créer le fichier .env s'il n'existe pas
if (!file_exists('.env')) {
    if (file_exists('env_production.txt')) {
        copy('env_production.txt', '.env');
        echo "✅ Fichier .env créé à partir de env_production.txt\n";
    } else {
        copy('env_template.txt', '.env');
        echo "✅ Fichier .env créé à partir de env_template.txt\n";
    }
}

// Générer la clé d'application
echo "Génération de la clé d'application...\n";
$key_result = shell_exec('php artisan key:generate --force 2>&1');
if (strpos($key_result, 'Application key set successfully') !== false) {
    echo "✅ Clé d'application générée\n";
} else {
    echo "⚠️  Problème avec la génération de clé: $key_result\n";
}

echo "\n4. Configuration de la base de données...\n";

// Créer le fichier SQLite s'il n'existe pas
if (!file_exists('database/database.sqlite')) {
    touch('database/database.sqlite');
    echo "✅ Fichier SQLite créé\n";
}

// Exécuter les migrations
echo "Exécution des migrations...\n";
$migrate_result = shell_exec('php artisan migrate --force 2>&1');
if (strpos($migrate_result, 'Migrated') !== false || strpos($migrate_result, 'Nothing to migrate') !== false) {
    echo "✅ Migrations exécutées\n";
} else {
    echo "⚠️  Problème avec les migrations: $migrate_result\n";
}

echo "\n5. Optimisation pour la production...\n";

// Créer le lien symbolique pour le storage
$storage_result = shell_exec('php artisan storage:link 2>&1');
if (strpos($storage_result, 'The link has been created') !== false) {
    echo "✅ Lien symbolique storage créé\n";
}

// Cache de configuration
$cache_commands = [
    'config:cache' => 'Configuration',
    'route:cache' => 'Routes',
    'view:cache' => 'Vues'
];

foreach ($cache_commands as $command => $description) {
    $result = shell_exec("php artisan $command 2>&1");
    if (strpos($result, 'successfully') !== false || empty(trim($result))) {
        echo "✅ Cache $description optimisé\n";
    } else {
        echo "⚠️  Problème avec le cache $description: $result\n";
    }
}

echo "\n6. Configuration des permissions...\n";

// Définir les permissions pour les dossiers critiques
$directories = ['storage', 'bootstrap/cache', 'database'];
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        chmod($dir, 0755);
        echo "✅ Permissions définies pour $dir\n";
    }
}

// Permissions pour le fichier SQLite
if (file_exists('database/database.sqlite')) {
    chmod('database/database.sqlite', 0644);
    echo "✅ Permissions définies pour database.sqlite\n";
}

echo "\n=== Déploiement terminé avec succès! ===\n";
echo "🎉 Ton application Ebitechs School ERP est prête!\n";
echo "\n📋 Prochaines étapes:\n";
echo "1. Configure ton domaine pour pointer vers le dossier 'public'\n";
echo "2. Active SSL dans cPanel (Let's Encrypt)\n";
echo "3. Configure les emails SMTP dans le fichier .env\n";
echo "4. Teste l'application en ligne\n";
echo "\n🔗 Accès admin: https://ton-domaine.com/admin\n";
echo "👤 Email par défaut: admin@example.com\n";
echo "🔑 Mot de passe: password\n";
echo "\n⚠️  N'oublie pas de changer les identifiants par défaut!\n";
