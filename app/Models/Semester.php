<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'name',
        'code',
        'start_date',
        'end_date',
        'enrollment_start_date',
        'enrollment_end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'enrollment_start_date' => 'date',
        'enrollment_end_date' => 'date',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isEnrollmentOpen()
    {
        $now = now()->toDateString();
        return $now >= $this->enrollment_start_date && $now <= $this->enrollment_end_date;
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function getDateRangeAttribute(): string
    {
        return $this->start_date->format('d M Y') . ' - ' . $this->end_date->format('d M Y');
    }

    public function getEnrollmentDateRangeAttribute(): string
    {
        return $this->enrollment_start_date->format('d M Y') . ' - ' . $this->enrollment_end_date->format('d M Y');
    }
}
