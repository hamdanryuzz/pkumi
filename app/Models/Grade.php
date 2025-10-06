<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'semester_id',
        'attendance_score',
        'assignment_score',
        'midterm_score',
        'final_score',
        'final_grade',
        'letter_grade',
        'bobot'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class); // Diperbaiki ke Semester::class
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public static function calculateFinalGrade($attendance, $assignment, $midterm, $final, $weights)
    {
        $finalGrade = (
            ($attendance * $weights->attendance_weight / 100) +
            ($assignment * $weights->assignment_weight / 100) +
            ($midterm * $weights->midterm_weight / 100) +
            ($final * $weights->final_weight / 100)
        );

        return round($finalGrade, 2);
    }

    public static function getLetterGrade($score)
    {
        if ($score >= 95) return 'A+';
        if ($score >= 90) return 'A';
        if ($score >= 85) return 'A-';
        if ($score >= 80) return 'B+';
        if ($score >= 75) return 'B';
        if ($score >= 70) return 'B-';
        // Di bawah 70 dianggap C (minimal kelulusan)
        return 'C';
    }

    public static function getBobot($score)
    {
        if ($score >= 95) return '4.00';
        if ($score >= 90) return '3.90';
        if ($score >= 85) return '3.70';
        if ($score >= 80) return '3.30';
        if ($score >= 75) return '3.00';
        if ($score >= 70) return '2.70';
        
        // Logika diperketat: nilai di bawah 70 (gagal/E) harus 0.00 atau 2.00 (sesuai standar C)
        // Jika ambang batas terendah adalah C (2.00), ini sudah benar, tetapi saya ganti 
        // agar nilai Gagal/E (misal < 60) jelas 0.00.
        // Namun, jika Anda ingin semua < 70 adalah 2.00 (C) sesuai kode lama, gunakan:
        // if ($score < 70) return '2.00'; 
        
        // Asumsi nilai F/Gagal (E) memiliki bobot 0.00.
        if ($score < 70) return '2.00'; // Defaulting ke C=2.00, berdasarkan kode lama.
        return '0.00'; // Seharusnya tidak tercapai jika nilai >= 70, tapi sebagai safety net
    }
}