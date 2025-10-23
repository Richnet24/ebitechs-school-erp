<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'supervisor_id',
        'max_members',
        'meeting_schedule',
        'location',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relations
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function members()
    {
        return $this->belongsToMany(Student::class, 'club_members')
                    ->withPivot(['role', 'joined_date', 'left_date', 'is_active'])
                    ->withTimestamps();
    }

    public function activeMembers()
    {
        return $this->members()->wherePivot('is_active', true);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getMembersCountAttribute()
    {
        return $this->activeMembers()->count();
    }

    public function getAvailableSlotsAttribute()
    {
        return $this->max_members - $this->members_count;
    }

    public function getIsFullAttribute()
    {
        return $this->members_count >= $this->max_members;
    }
}