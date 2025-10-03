<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'name',
        'username',
        'email',
        'password',
        'phone',
        'address',
        'status',
        'student_class_id',
        'year_id'
    ];

    public function grade()
    {
        return $this->hasOne(Grade::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function periods()
    {
        return $this->belongsToMany(Period::class, 'enrollments')
            ->withPivot(['course_id', 'enrollment_date', 'status'])
            ->withTimestamps();
    }

    public function coursesInPeriod($periodId)
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->wherePivot('period_id', $periodId)
            ->wherePivot('status', 'enrolled')
            ->withPivot(['enrollment_date', 'status'])
            ->withTimestamps();
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'grades')
            ->withPivot(['period_id', 'attendance_score', 'assignment_score', 'midterm_score', 'final_score', 'final_grade', 'letter_grade'])
            ->withTimestamps();
    }

}