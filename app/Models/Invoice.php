<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'student_id',
        'description',
        'amount',
        'tax_amount',
        'total_amount',
        'invoice_date',
        'due_date',
        'status',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'invoice_date' => 'date',
            'due_date' => 'date',
        ];
    }

    // Relations
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['draft', 'sent']);
    }

    // Accessors
    public function getPaidAmountAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getIsPaidAttribute()
    {
        return $this->remaining_amount <= 0;
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date < now() && !$this->is_paid;
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($invoice) {
            $invoice->updateStatus();
        });
    }

    public function updateStatus()
    {
        $paidAmount = $this->paid_amount;
        $totalAmount = $this->total_amount;
        
        if ($paidAmount >= $totalAmount) {
            $this->update(['status' => 'paid']);
        } elseif ($this->due_date < now()) {
            $this->update(['status' => 'overdue']);
        } elseif ($paidAmount > 0) {
            $this->update(['status' => 'sent']);
        }
    }
}
