<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Budget;
use App\Models\Invoice;
use App\Models\Grade;
use App\Models\StockItem;

echo "=== SYNCHRONISATION DES DONNÉES ===\n\n";

// 1. Synchroniser les budgets
echo "1. Synchronisation des budgets...\n";
$budgets = Budget::all();
foreach ($budgets as $budget) {
    $oldSpent = $budget->spent_amount;
    $oldRemaining = $budget->remaining_amount;
    
    $newSpent = $budget->spent_amount; // Utilise l'accessor
    $newRemaining = $budget->remaining_amount; // Utilise l'accessor
    
    if ($oldSpent != $newSpent || $oldRemaining != $newRemaining) {
        echo "  Budget '{$budget->name}': Spent {$oldSpent} → {$newSpent}, Remaining {$oldRemaining} → {$newRemaining}\n";
    }
}
echo "  ✓ Budgets synchronisés\n\n";

// 2. Synchroniser les factures
echo "2. Synchronisation des factures...\n";
$invoices = Invoice::all();
$updated = 0;
foreach ($invoices as $invoice) {
    $oldStatus = $invoice->status;
    $invoice->updateStatus();
    if ($invoice->status != $oldStatus) {
        echo "  Facture '{$invoice->invoice_number}': {$oldStatus} → {$invoice->status}\n";
        $updated++;
    }
}
echo "  ✓ {$updated} factures mises à jour\n\n";

// 3. Synchroniser les notes
echo "3. Synchronisation des notes...\n";
$grades = Grade::all();
$updated = 0;
foreach ($grades as $grade) {
    $oldGpa = $grade->gpa;
    $grade->save(); // Déclenche le calcul automatique du GPA
    if ($grade->gpa != $oldGpa) {
        echo "  Note ID {$grade->id}: GPA {$oldGpa} → {$grade->gpa}\n";
        $updated++;
    }
}
echo "  ✓ {$updated} notes mises à jour\n\n";

// 4. Synchroniser les stocks
echo "4. Synchronisation des stocks...\n";
$items = StockItem::all();
$updated = 0;
foreach ($items as $item) {
    $oldStock = $item->current_stock;
    $newStock = $item->current_stock; // Utilise l'accessor
    
    if ($oldStock != $newStock) {
        $item->update(['current_stock' => $newStock]);
        echo "  Article '{$item->name}': {$oldStock} → {$newStock}\n";
        $updated++;
    }
}
echo "  ✓ {$updated} articles de stock mis à jour\n\n";

echo "=== SYNCHRONISATION TERMINÉE ===\n";
