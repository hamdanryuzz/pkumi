<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentClass extends Model
{
    use HasFactory, SoftDeletes; // Tambah soft delete untuk hapus aman

    protected $table = 'student_classes';

    protected $fillable = [
        'year_id', // Tambah year_id untuk mass assignment
        'name',
    ];

    /**
     * Relasi ke Year (untuk dropdown dan query).
     */
    public function year()
    {
        return $this->belongsTo(Year::class); // Tambah relasi Year
    }

    /**
     * Relasi ke Student (sudah ada, tapi optimasi kolom jika perlu).
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    /**
     * Accessor untuk nama lengkap (opsional, untuk tampilan).
     */
    public function getFullNameAttribute()
    {
        return $this->name . ' - ' . ($this->year->name ?? 'Tahun tidak diketahui');
    }
}