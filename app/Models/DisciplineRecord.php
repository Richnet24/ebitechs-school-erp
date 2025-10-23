<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DisciplineRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'incident_date',
        'type',
        'title',
        'description',
        'actions_taken',
        'consequences',
        'follow_up',
        'status',
        'reported_by',
        'handled_by',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'incident_date' => 'date',
            'resolved_at' => 'datetime',
        ];
    }

    // Relations
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    // Accessors
    public function getIsResolvedAttribute()
    {
        return $this->status === 'resolved';
    }

    public function getIsOpenAttribute()
    {
        return $this->status === 'open';
    }

    public function getSeverityColorAttribute()
    {
        return match ($this->type) {
            'minor' => 'warning',
            'major' => 'danger',
            'serious' => 'danger',
            default => 'gray',
        };
    }
}