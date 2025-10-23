<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'class_id',
        'teacher_id',
        'name',
        'description',
        'credits',
        'hours_per_week',
        'start_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    // Relations
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    // Accessors
    public function getStudentsCountAttribute()
    {
        return $this->class->students()->count();
    }

    public function getDurationWeeksAttribute()
    {
        return $this->start_date && $this->end_date ? 
            $this->start_date->diffInWeeks($this->end_date) : 0;
    }
}
