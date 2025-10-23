<?php

// Script pour configurer l'application Laravel
echo "Configuration de l'application Laravel...\n";

// 1. Générer une clé d'application
$key = 'base64:' . base64_encode(random_bytes(32));
echo "Clé générée: " . $key . "\n";

// 2. Créer le fichier .env
$envContent = 'APP_NAME="Ebitechs School ERP"
APP_ENV=local
APP_KEY=' . $key . '
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
';

file_put_contents('.env', $envContent);
echo "Fichier .env créé\n";

// 3. Vérifier si la base de données SQLite existe
if (!file_exists('database/database.sqlite')) {
    touch('database/database.sqlite');
    echo "Base de données SQLite créée\n";
} else {
    echo "Base de données SQLite existe déjà\n";
}

echo "Configuration terminée!\n";
echo "Vous pouvez maintenant exécuter: php artisan migrate:fresh --seed\n";
