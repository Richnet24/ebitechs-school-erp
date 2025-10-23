<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'user_id',
        'occupation',
        'workplace',
        'work_phone',
        'relationship',
        'notes',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function children()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    // Scopes
    public function scopeByRelationship($query, $relationship)
    {
        return $query->where('relationship', $relationship);
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

    public function getChildrenCountAttribute()
    {
        return $this->children()->count();
    }
}
