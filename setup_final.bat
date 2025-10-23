@echo off
echo Configuration de l'application Laravel...

REM Generer une cle d'application
php artisan key:generate

REM Executer les migrations
php artisan migrate:fresh

REM Executer les seeders
php artisan db:seed

echo Configuration terminee!
pause