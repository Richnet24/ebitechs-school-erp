<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_number',
        'specialization',
        'qualification',
        'hire_date',
        'salary',
        'employment_type',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'hire_date' => 'date',
            'salary' => 'decimal:2',
        ];
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'teacher_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'graded_by');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'marked_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByEmploymentType($query, $type)
    {
        return $query->where('employment_type', $type);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->user->name ?? 'N/A';
    }

    public function getEmailAttribute()
    {
        return $this->user->email ?? 'N/A';
    }

    public function getPhoneAttribute()
    {
        return $this->user->phone ?? 'N/A';
    }

    public function getExperienceYearsAttribute()
    {
        return $this->hire_date ? $this->hire_date->diffInYears(now()) : 0;
    }

    public function getCoursesCountAttribute()
    {
        return $this->courses()->count();
    }

    public function getStudentsCountAttribute()
    {
        return $this->courses()->withCount('students')->sum('students_count');
    }
}
