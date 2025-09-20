<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
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

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'grades')
                    ->withPivot(['attendance_score', 'assignment_score', 'midterm_score', 'final_score', 'final_grade', 'letter_grade'])
                    ->withTimestamps();
    }

}