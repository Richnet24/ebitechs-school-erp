<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'record_date',
        'type',
        'description',
        'diagnosis',
        'treatment',
        'medication',
        'notes',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'record_date' => 'date',
        ];
    }

    // Relations
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('record_date', $date);
    }
}
