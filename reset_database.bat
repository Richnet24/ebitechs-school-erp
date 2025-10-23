@echo off
echo ========================================
echo    RESET COMPLET DE LA BASE DE DONNEES
echo ========================================
echo.

echo 1. Arret du serveur Laravel...
taskkill /f /im php.exe 2>nul

echo.
echo 2. Suppression de la base de donnees existante...
if exist "database\database.sqlite" del "database\database.sqlite"

echo.
echo 3. Creation d'une nouvelle base de donnees...
type nul > database\database.sqlite

echo.
echo 4. Execution des migrations...
php artisan migrate --force

echo.
echo 5. Execution des seeders...
php artisan db:seed --force

echo.
echo 6. Verification des donnees...
php artisan tinker --execute="echo 'Utilisateurs: ' . App\Models\User::count() . PHP_EOL; echo 'Etudiants: ' . App\Models\Student::count() . PHP_EOL; echo 'Enseignants: ' . App\Models\Teacher::count() . PHP_EOL;"

echo.
echo 7. Lancement du serveur...
echo L'application sera accessible sur: http://127.0.0.1:8000
echo.
php artisan serve
