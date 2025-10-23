<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'exam_id',
        'marks_obtained',
        'total_marks',
        'grade_letter',
        'gpa',
        'remarks',
        'graded_by',
        'graded_at',
    ];

    protected function casts(): array
    {
        return [
            'marks_obtained' => 'decimal:2',
            'total_marks' => 'decimal:2',
            'gpa' => 'decimal:2',
            'graded_at' => 'datetime',
        ];
    }

    // Relations
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    // Scopes
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }

    // Accessors
    public function getPercentageAttribute()
    {
        return $this->total_marks > 0 ? 
            round(($this->marks_obtained / $this->total_marks) * 100, 2) : 0;
    }

    public function getIsPassingAttribute()
    {
        return $this->percentage >= 50; // Assuming 50% is passing
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($grade) {
            // Calculer automatiquement le GPA basé sur le pourcentage
            $percentage = $grade->total_marks > 0 ? 
                ($grade->marks_obtained / $grade->total_marks) * 100 : 0;
            
            $grade->gpa = match (true) {
                $percentage >= 90 => 4.0,
                $percentage >= 80 => 3.5,
                $percentage >= 70 => 3.0,
                $percentage >= 60 => 2.5,
                $percentage >= 50 => 2.0,
                default => 0.0,
            };
        });
    }
}
