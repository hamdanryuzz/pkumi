<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_class_id', 'course_id', 'semester_id', 
        'enrollment_date', 'status'
    ];

    protected $casts = [
        'enrollment_date' => 'date',
    ];

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class); // Konsistensi: Semester::class
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'enrolled');
    }
}