<?php

// Script pour créer la base de données et exécuter les migrations
echo "=== Configuration de la base de données Ebitechs School ERP ===\n";

// Vérifier si la base de données existe
$dbPath = __DIR__ . '/database/database.sqlite';
if (!file_exists($dbPath)) {
    echo "Création de la base de données SQLite...\n";
    touch($dbPath);
    echo "Base de données créée : $dbPath\n";
} else {
    echo "Base de données existante trouvée : $dbPath\n";
}

// Supprimer les migrations dupliquées
$migrationsDir = __DIR__ . '/database/migrations/';
$duplicateFiles = [
    '2025_09_25_222806_create_class_models_table.php',
    '2025_09_25_222857_create_parent_models_table.php',
    '2025_09_25_223103_create_stock_movements_table.php',
    '2025_09_25_223216_create_budgets_table.php',
    '2025_09_25_223309_create_notifications_table.php',
    '2025_09_25_223342_create_audit_logs_table.php'
];

echo "Suppression des migrations dupliquées...\n";
foreach ($duplicateFiles as $file) {
    $filePath = $migrationsDir . $file;
    if (file_exists($filePath)) {
        unlink($filePath);
        echo "Supprimé : $file\n";
    }
}

echo "\n=== Configuration terminée ===\n";
echo "Maintenant exécutez :\n";
echo "1. php artisan migrate:fresh --seed\n";
echo "2. php artisan serve\n";
echo "\n";
