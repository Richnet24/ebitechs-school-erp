<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'category',
        'unit',
        'current_stock',
        'minimum_stock',
        'maximum_stock',
        'unit_cost',
        'selling_price',
        'location',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'unit_cost' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // Relations
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock', '<=', 'minimum_stock');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getCurrentStockAttribute()
    {
        // Calculer le stock actuel à partir des mouvements
        $inMovements = $this->stockMovements()->where('type', 'in')->sum('quantity');
        $outMovements = $this->stockMovements()->where('type', 'out')->sum('quantity');
        
        return $inMovements - $outMovements;
    }

    public function getIsLowStockAttribute()
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    public function getStockValueAttribute()
    {
        return $this->current_stock * $this->unit_cost;
    }

    public function getTotalValueAttribute()
    {
        return $this->current_stock * $this->unit_cost;
    }

    // Méthodes utilitaires
    public function syncCurrentStock()
    {
        $calculatedStock = $this->current_stock;
        $this->update(['current_stock' => $calculatedStock]);
        return $calculatedStock;
    }

    public static function syncAllStocks()
    {
        $items = self::all();
        foreach ($items as $item) {
            $item->syncCurrentStock();
        }
    }
}
