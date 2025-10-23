<?php

// Script pour générer une clé d'application Laravel
$key = 'base64:' . base64_encode(random_bytes(32));
echo "Clé générée : " . $key . "\n";

// Lire le fichier .env s'il existe
$envFile = '.env';
if (file_exists($envFile)) {
    $content = file_get_contents($envFile);
    
    // Remplacer ou ajouter APP_KEY
    if (strpos($content, 'APP_KEY=') !== false) {
        $content = preg_replace('/APP_KEY=.*/', 'APP_KEY=' . $key, $content);
    } else {
        $content = "APP_KEY=" . $key . "\n" . $content;
    }
    
    file_put_contents($envFile, $content);
    echo "Clé ajoutée au fichier .env\n";
} else {
    echo "Fichier .env non trouvé. Création d'un fichier .env de base...\n";
    
    $envContent = 'APP_NAME="Ebitechs School ERP"
APP_ENV=local
APP_KEY=' . $key . '
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost

APP_LOCALE=fr
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=fr_FR

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"';
    
    file_put_contents($envFile, $envContent);
    echo "Fichier .env créé avec la clé\n";
}

echo "Configuration terminée !\n";
