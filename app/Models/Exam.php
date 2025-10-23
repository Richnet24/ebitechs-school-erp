<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'name',
        'description',
        'type',
        'exam_date',
        'start_time',
        'end_time',
        'total_marks',
        'passing_marks',
        'room',
        'instructions',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'exam_date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
        ];
    }

    // Relations
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    // Scopes
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Accessors
    public function getDurationAttribute()
    {
        return $this->start_time && $this->end_time ? 
            $this->start_time->diffInMinutes($this->end_time) : 0;
    }

    public function getStudentsCountAttribute()
    {
        return $this->course->students_count;
    }

    public function getAverageGradeAttribute()
    {
        return $this->grades()->avg('marks_obtained') ?? 0;
    }
}
