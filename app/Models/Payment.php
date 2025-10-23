<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'payment_number',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'notes',
        'received_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    // Merge custom creating hook into existing boot below

    // Relations
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // Scopes
    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('payment_date', $date);
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        // Attribuer automatiquement le réceptionnaire si absent
        static::creating(function (Payment $payment) {
            if (empty($payment->received_by) && auth()->check()) {
                $payment->received_by = auth()->id();
            }
        });

        static::created(function ($payment) {
            $payment->invoice->updateStatus();
        });

        static::updated(function ($payment) {
            $payment->invoice->updateStatus();
        });

        static::deleted(function ($payment) {
            $payment->invoice->updateStatus();
        });
    }
}
