<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'student_classes';

    protected $fillable = [
        'year_id',
        'name',
    ];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'student_class_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'student_class_id');
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' - ' . ($this->year->name ?? 'Tahun tidak diketahui');
    }
}