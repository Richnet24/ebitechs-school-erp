@echo off
echo Configuration de Laravel...

REM Créer le fichier .env avec une clé générée
echo APP_NAME="Ebitechs School ERP" > .env
echo APP_ENV=local >> .env
echo APP_KEY=base64:YOUR_APP_KEY_HERE >> .env
echo APP_DEBUG=true >> .env
echo APP_TIMEZONE=UTC >> .env
echo APP_URL=http://localhost >> .env
echo. >> .env
echo DB_CONNECTION=sqlite >> .env
echo DB_DATABASE=database/database.sqlite >> .env
echo. >> .env
echo SESSION_DRIVER=database >> .env
echo CACHE_STORE=database >> .env
echo QUEUE_CONNECTION=database >> .env

echo Fichier .env créé!
echo.
echo IMPORTANT: Vous devez maintenant:
echo 1. Exécuter: php artisan key:generate
echo 2. Exécuter: php artisan migrate
echo 3. Exécuter: php artisan db:seed
echo 4. Démarrer le serveur: php artisan serve
echo.
pause
