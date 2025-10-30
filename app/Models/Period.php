<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function years(): HasMany
    {
        return $this->hasMany(Year::class);
    }

    /**
     * Relationship with Semester model
     * One Period has many Semesters
     */
    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class);
    }

    /**
     * Scope to filter active periods
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to filter completed periods
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to filter draft periods
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Check if period is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if period is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if period is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function getCleanNameAttribute(): string
    {
        $clean = preg_replace('/[^0-9\/-]+/', '', (string) $this->name);
        $clean = preg_replace('/[\/-]{2,}/', '/', $clean);
        return trim((string) $clean, " /-");
    }
}
