<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Requisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_number',
        'description',
        'justification',
        'estimated_cost',
        'required_date',
        'priority',
        'status',
        'requested_by',
        'approved_by',
        'approved_at',
        'approval_notes',
    ];

    protected function casts(): array
    {
        return [
            'estimated_cost' => 'decimal:2',
            'required_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    // Relations
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->whereIn('status', ['draft', 'submitted']);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Accessors
    public function getIsApprovedAttribute()
    {
        return $this->status === 'approved';
    }

    public function getIsPendingAttribute()
    {
        return in_array($this->status, ['draft', 'submitted']);
    }
}
