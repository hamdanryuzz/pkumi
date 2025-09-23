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
        'attendance_score',
        'assignment_score',
        'midterm_score',
        'final_score',
        'final_grade',
        'letter_grade'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class); // bukan hasMany
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
        return 'C';
    }
}