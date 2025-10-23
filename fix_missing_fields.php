<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CORRECTION DES CHAMPS MANQUANTS ===\n\n";

// Vérifier et corriger les factures existantes sans created_by
$invoicesWithoutCreator = \App\Models\Invoice::whereNull('created_by')->get();
echo "Factures sans créateur: " . $invoicesWithoutCreator->count() . "\n";

if ($invoicesWithoutCreator->count() > 0) {
    $adminId = \App\Models\User::where('email', 'admin@ebitechs.edu')->first()?->id ?? 1;
    
    foreach ($invoicesWithoutCreator as $invoice) {
        $invoice->update(['created_by' => $adminId]);
        echo "  - Facture {$invoice->invoice_number} assignée à l'admin\n";
    }
}

// Vérifier et corriger les dépenses existantes sans created_by
$expensesWithoutCreator = \App\Models\Expense::whereNull('created_by')->get();
echo "Dépenses sans créateur: " . $expensesWithoutCreator->count() . "\n";

if ($expensesWithoutCreator->count() > 0) {
    $adminId = \App\Models\User::where('email', 'admin@ebitechs.edu')->first()?->id ?? 1;
    
    foreach ($expensesWithoutCreator as $expense) {
        $expense->update(['created_by' => $adminId]);
        echo "  - Dépense {$expense->title} assignée à l'admin\n";
    }
}

// Vérifier et corriger les budgets existants sans created_by
$budgetsWithoutCreator = \App\Models\Budget::whereNull('created_by')->get();
echo "Budgets sans créateur: " . $budgetsWithoutCreator->count() . "\n";

if ($budgetsWithoutCreator->count() > 0) {
    $adminId = \App\Models\User::where('email', 'admin@ebitechs.edu')->first()?->id ?? 1;
    
    foreach ($budgetsWithoutCreator as $budget) {
        $budget->update(['created_by' => $adminId]);
        echo "  - Budget {$budget->name} assigné à l'admin\n";
    }
}

echo "\n=== CORRECTION TERMINÉE ===\n";

