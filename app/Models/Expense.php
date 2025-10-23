<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_number',
        'title',
        'description',
        'category',
        'amount',
        'currency',
        'expense_date',
        'status',
        'payment_method',
        'vendor_name',
        'vendor_contact',
        'reference_number',
        'notes',
        'attachments',
        'budget_id',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
        'attachments' => 'array',
        'approved_at' => 'datetime',
    ];

    // Relations
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('expense_date', [$startDate, $endDate]);
    }

    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending_approval');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Accessors & Mutators
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'Brouillon',
            'pending_approval' => 'En attente d\'approbation',
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
            'paid' => 'Payé',
            'cancelled' => 'Annulé',
            default => $this->status,
        };
    }

    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            'academic' => 'Académique',
            'administrative' => 'Administratif',
            'infrastructure' => 'Infrastructure',
            'maintenance' => 'Maintenance',
            'equipment' => 'Équipement',
            'personnel' => 'Personnel',
            'utilities' => 'Services publics',
            'transport' => 'Transport',
            'communication' => 'Communication',
            'other' => 'Autre',
            default => $this->category,
        };
    }

    // Boot method pour générer le numéro de dépense
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($expense) {
            if (empty($expense->expense_number)) {
                $expense->expense_number = 'EXP-' . date('Y') . '-' . str_pad(Expense::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
