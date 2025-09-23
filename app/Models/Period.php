<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'start_date', 'end_date',
        'enrollment_start_date', 'enrollment_end_date', 'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'enrollment_start_date' => 'date',
        'enrollment_end_date' => 'date',
    ];

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
}
