<?php

namespace App\Console\Commands;

use App\Models\StockItem;
use Illuminate\Console\Command;

class SyncStockLevels extends Command
{
    protected $signature = 'stock:sync';
    protected $description = 'Synchronise les niveaux de stock avec les mouvements de stock';

    public function handle()
    {
        $this->info('Synchronisation des niveaux de stock...');
        
        $items = StockItem::all();
        $synced = 0;
        
        foreach ($items as $item) {
            $oldStock = $item->current_stock;
            $newStock = $item->current_stock; // Utilise l'accessor qui calcule depuis les mouvements
            
            if ($oldStock != $newStock) {
                $item->update(['current_stock' => $newStock]);
                $this->line("Article '{$item->name}': {$oldStock} → {$newStock}");
                $synced++;
            }
        }
        
        $this->info("Synchronisation terminée. {$synced} articles mis à jour.");
        
        return Command::SUCCESS;
    }
}