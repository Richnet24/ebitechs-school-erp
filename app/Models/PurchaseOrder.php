<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'requisition_id',
        'supplier_name',
        'supplier_contact',
        'description',
        'total_amount',
        'order_date',
        'expected_delivery_date',
        'status',
        'terms_conditions',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'order_date' => 'date',
            'expected_delivery_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    // Relations
    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->whereIn('status', ['draft', 'sent']);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    // Accessors
    public function getIsDeliveredAttribute()
    {
        return $this->status === 'delivered';
    }

    public function getIsPendingAttribute()
    {
        return in_array($this->status, ['draft', 'sent']);
    }
}
