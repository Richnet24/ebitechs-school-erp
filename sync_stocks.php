<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\StockItem;
use App\Models\StockMovement;

echo "Synchronisation des niveaux de stock...\n";

$items = StockItem::all();
$synced = 0;

foreach ($items as $item) {
    $oldStock = $item->current_stock;
    
    // Calculer le nouveau stock à partir des mouvements
    $inMovements = $item->stockMovements()->where('type', 'in')->sum('quantity');
    $outMovements = $item->stockMovements()->where('type', 'out')->sum('quantity');
    $newStock = $inMovements - $outMovements;
    
    if ($oldStock != $newStock) {
        $item->update(['current_stock' => $newStock]);
        echo "Article '{$item->name}': {$oldStock} → {$newStock}\n";
        $synced++;
    }
}

echo "Synchronisation terminée. {$synced} articles mis à jour.\n";
