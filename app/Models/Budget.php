<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'allocated_amount',
        'spent_amount',
        'remaining_amount',
        'fiscal_year',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'allocated_amount' => 'decimal:2',
            'spent_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    // Relations
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByFiscalYear($query, $year)
    {
        return $query->where('fiscal_year', $year);
    }

    // Relations
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    // Accessors
    public function getSpentAmountAttribute()
    {
        return $this->expenses()->whereIn('status', ['approved', 'paid'])->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->allocated_amount - $this->spent_amount;
    }

    public function getUtilizationPercentageAttribute()
    {
        return $this->allocated_amount > 0 ? 
            round(($this->spent_amount / $this->allocated_amount) * 100, 2) : 0;
    }

    public function getIsOverBudgetAttribute()
    {
        return $this->spent_amount > $this->allocated_amount;
    }
}
