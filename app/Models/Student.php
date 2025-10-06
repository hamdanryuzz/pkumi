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

    // PERUBAHAN UTAMA:
    // 1. Mengubah hasOne(Grade::class) menjadi hasMany(Grade::class)
    // 2. Mengubah nama relasi dari grade() menjadi grades()
    public function grades()
    {
        return $this->hasMany(Grade::class);
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

    // PERUBAHAN UTAMA:
    // Mengubah nama relasi dari semester() menjadi semesters() (untuk konsistensi many-to-many)
    public function semesters()
    {
        return $this->belongsToMany(Semester::class, 'enrollments')
            ->withPivot(['course_id', 'enrollment_date', 'status'])
            ->withTimestamps();
    }

    public function coursesInSemester($semesterId)
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->wherePivot('semester_id', $semesterId)
            ->wherePivot('status', 'enrolled')
            ->withPivot(['enrollment_date', 'status'])
            ->withTimestamps();
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'grades')
            ->withPivot(['semester_id', 'attendance_score', 'assignment_score', 'midterm_score', 'final_score', 'final_grade', 'letter_grade'])
            ->withTimestamps();
    }
}