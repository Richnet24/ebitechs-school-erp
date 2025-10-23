<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_number',
        'class_id',
        'parent_id',
        'admission_date',
        'birth_date',
        'birth_place',
        'nationality',
        'religion',
        'blood_type',
        'medical_notes',
        'emergency_contact',
        'emergency_phone',
        'status',
        'qr_code',
    ];

    protected function casts(): array
    {
        return [
            'admission_date' => 'date',
            'birth_date' => 'date',
        ];
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }

    public function psychologicalRecords()
    {
        return $this->hasMany(PsychologicalRecord::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
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

    public function getAddressAttribute()
    {
        return $this->user->address ?? 'N/A';
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function getAverageGradeAttribute()
    {
        return $this->grades()->avg('marks_obtained') ?? 0;
    }

    public function getAttendancePercentageAttribute()
    {
        $total = $this->attendances()->count();
        if ($total === 0) return 0;
        
        $present = $this->attendances()->where('status', 'present')->count();
        return round(($present / $total) * 100, 2);
    }
}
