<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_item_id',
        'type',
        'quantity',
        'unit_cost',
        'reason',
        'reference_number',
        'purchase_order_id',
        'processed_by',
    ];

    protected function casts(): array
    {
        return [
            'unit_cost' => 'decimal:2',
        ];
    }

    // Relations
    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Scopes
    public function scopeIn($query)
    {
        return $query->where('type', 'in');
    }

    public function scopeOut($query)
    {
        return $query->where('type', 'out');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getTotalValueAttribute()
    {
        return $this->quantity * $this->unit_cost;
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::created(function ($movement) {
            $movement->updateStockItem();
        });

        static::updated(function ($movement) {
            $movement->updateStockItem();
        });

        static::deleted(function ($movement) {
            $movement->updateStockItem();
        });
    }

    public function updateStockItem()
    {
        $stockItem = $this->stockItem;
        if ($stockItem) {
            $inMovements = $stockItem->stockMovements()->where('type', 'in')->sum('quantity');
            $outMovements = $stockItem->stockMovements()->where('type', 'out')->sum('quantity');
            $newStock = $inMovements - $outMovements;
            
            $stockItem->update(['current_stock' => $newStock]);
        }
    }
}
