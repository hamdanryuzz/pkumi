<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class Student extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        // Field lama (tidak diubah)
        'nim',
        'name',
        'username',
        'email',
        'password',
        'phone',
        'image',
        'address',
        'status',
        'student_class_id',
        'year_id',

        // 🔽 Field tambahan dari migration
        'gender',
        'place_of_birth',
        'date_of_birth',
        'student_job',
        'marital_status',
        'program',
        'admission_year',
        'first_semester',
        'origin_of_university',
        'initial_study_program',
        'graduation_year',
        'gpa',
        'father_name',
        'father_last_education',
        'father_job',
        'mother_name',
        'mother_last_education',
        'mother_job',
        'street',
        'rt_rw',
        'village',
        'district',
        'city',
        'province',
        'description',
    ];

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

    public function getIpkAttribute()
    {
        $grades = $this->grades()->with('course')->get();

        $totalBobotX_Sks = $grades->sum(function($grade) {
            if ($grade->bobot && $grade->course && $grade->course->sks) {
                return (float)$grade->bobot * $grade->course->sks;
            }
            return 0;
        });

        $totalSks = $grades->sum(function($grade) {
            if ($grade->bobot && $grade->course && $grade->course->sks) {
                return $grade->course->sks;
            }
            return 0;
        });

        if ($totalSks > 0) {
            return round($totalBobotX_Sks / $totalSks, 2);
        }

        return 0.00;
    }

    public function getImageUrlAttribute()
    {
        if ($this->image && \Storage::exists('public/students/' . $this->image)) {
            return \Storage::url('students/' . $this->image);
        }
        return asset('images/default-avatar.png');
    }
}
