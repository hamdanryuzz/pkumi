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

    public function grades()
    {
        // Relasi Mahasiswa memiliki banyak nilai
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

    /**
     * ACCESSOR: Menghitung IPK (Indeks Prestasi Kumulatif) Mahasiswa.
     * Dapat dipanggil sebagai $student->ipk
     */
    public function getIpkAttribute()
    {
        // Eager load courses untuk mendapatkan SKS (karena grades tidak punya relasi langsung ke course)
        $grades = $this->grades()->with('course')->get();

        // Total Bobot (Bobot Nilai * SKS)
        $totalBobotX_Sks = $grades->sum(function($grade) {
            // Pastikan semua komponen nilai dan SKS tersedia
            if ($grade->bobot && $grade->course && $grade->course->sks) {
                // Konversi string bobot nilai (misal '3.70') menjadi float
                return (float)$grade->bobot * $grade->course->sks;
            }
            return 0;
        });

        // Total SKS yang diperhitungkan (SKS mata kuliah yang sudah memiliki nilai final)
        $totalSks = $grades->sum(function($grade) {
            // Hanya hitung SKS jika grade tersebut memiliki nilai bobot (asumsi nilai sudah final)
            if ($grade->bobot && $grade->course && $grade->course->sks) {
                return $grade->course->sks;
            }
            return 0;
        });

        // Hitung IPK = Total Bobot x SKS / Total SKS
        if ($totalSks > 0) {
            return round($totalBobotX_Sks / $totalSks, 2);
        }

        return 0.00;
    }
}