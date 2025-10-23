<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PsychologicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'session_date',
        'type',
        'description',
        'observations',
        'recommendations',
        'follow_up_actions',
        'notes',
        'psychologist_id',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'date',
        ];
    }

    // Relations
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function psychologist()
    {
        return $this->belongsTo(User::class, 'psychologist_id');
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('session_date', $date);
    }
}
