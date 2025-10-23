<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'code',
        'branch_id',
        'level',
        'capacity',
        'teacher_id',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relations
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    // Accessors
    public function getStudentsCountAttribute()
    {
        return $this->students()->count();
    }

    public function getAvailableSlotsAttribute()
    {
        return $this->capacity - $this->students_count;
    }

    public function getIsFullAttribute()
    {
        return $this->students_count >= $this->capacity;
    }
}
