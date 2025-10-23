@echo off
echo ========================================
echo    LANCEMENT DE L'APPLICATION LARAVEL
echo ========================================
echo.

echo 1. Verification de PHP...
php -v
if %errorlevel% neq 0 (
    echo ERREUR: PHP n'est pas installe ou accessible
    pause
    exit /b 1
)

echo.
echo 2. Verification de la base de données...
if not exist "database\database.sqlite" (
    echo Creation de la base de données SQLite...
    type nul > database\database.sqlite
)

echo.
echo 3. Execution des migrations...
php artisan migrate --force

echo.
echo 4. Execution des seeders...
php artisan db:seed --force

echo.
echo 5. Lancement du serveur Laravel...
echo L'application sera accessible sur: http://127.0.0.1:8000
echo.
echo Appuyez sur Ctrl+C pour arreter le serveur
echo.

php artisan serve
