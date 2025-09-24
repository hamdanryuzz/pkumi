<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'student_class_id'];

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function periods()
    {
        return $this->belongsToMany(Period::class, 'enrollments')
            ->withPivot(['student_id', 'enrollment_date', 'status'])
            ->withTimestamps();
    }

    public function studentsInPeriod($periodId)
    {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->wherePivot('period_id', $periodId)
            ->wherePivot('status', 'enrolled')
            ->withPivot(['enrollment_date', 'status'])
            ->withTimestamps();
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'grades')
            ->withPivot(['period_id', 'attendance_score', 'assignment_score', 'midterm_score', 'final_score', 'final_grade', 'letter_grade'])
            ->withTimestamps();
    }

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'student_class_id');
    }
    
}
