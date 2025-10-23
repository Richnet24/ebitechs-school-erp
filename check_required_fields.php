<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "=== VÉRIFICATION DES CHAMPS REQUIS ===\n\n";

// Modèles à vérifier
$models = [
    'Invoice' => ['created_by'],
    'Expense' => ['created_by', 'approved_by'],
    'Budget' => ['created_by', 'approved_by'],
    'StockMovement' => ['processed_by'],
    'Grade' => ['graded_by'],
    'Attendance' => ['marked_by'],
];

foreach ($models as $modelName => $requiredFields) {
    echo "Modèle: {$modelName}\n";
    
    $tableName = strtolower($modelName) . 's';
    if ($modelName === 'Invoice') $tableName = 'invoices';
    if ($modelName === 'Expense') $tableName = 'expenses';
    if ($modelName === 'Budget') $tableName = 'budgets';
    if ($modelName === 'StockMovement') $tableName = 'stock_movements';
    if ($modelName === 'Grade') $tableName = 'grades';
    if ($modelName === 'Attendance') $tableName = 'attendances';
    
    foreach ($requiredFields as $field) {
        $hasColumn = Schema::hasColumn($tableName, $field);
        echo "  - {$field}: " . ($hasColumn ? "✓ Présent" : "✗ Manquant") . "\n";
    }
    echo "\n";
}

echo "=== VÉRIFICATION TERMINÉE ===\n";

