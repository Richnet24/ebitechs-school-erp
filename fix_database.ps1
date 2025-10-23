# Script PowerShell pour résoudre le problème de base de données
Write-Host "=== Résolution du problème de base de données ===" -ForegroundColor Green

# Arrêter le serveur s'il tourne
Write-Host "Arrêt du serveur Laravel..." -ForegroundColor Yellow
Get-Process | Where-Object {$_.ProcessName -eq "php"} | Stop-Process -Force -ErrorAction SilentlyContinue

# Supprimer la base de données existante
$dbPath = "database\database.sqlite"
if (Test-Path $dbPath) {
    Write-Host "Suppression de l'ancienne base de données..." -ForegroundColor Yellow
    Remove-Item $dbPath -Force
}

# Créer une nouvelle base de données
Write-Host "Création d'une nouvelle base de données..." -ForegroundColor Yellow
New-Item -Path $dbPath -ItemType File -Force | Out-Null

# Exécuter les migrations
Write-Host "Exécution des migrations..." -ForegroundColor Yellow
php artisan migrate:fresh --seed --force

# Vérifier le statut
Write-Host "Vérification du statut des migrations..." -ForegroundColor Yellow
php artisan migrate:status

Write-Host "=== Configuration terminée ===" -ForegroundColor Green
Write-Host "Vous pouvez maintenant démarrer le serveur avec: php artisan serve" -ForegroundColor Cyan
