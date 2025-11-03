<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'class_pattern', 'sks'];

    public function grades()
    {
        return $this->hasMany(Grade::class);
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
            ->withPivot(['student_class_id', 'enrollment_date', 'status'])
            ->withTimestamps();
    }

    public function studentsInSemester($semesterId)
    {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->wherePivot('semester_id', $semesterId)
            ->wherePivot('status', 'enrolled')
            ->withPivot(['enrollment_date', 'status'])
            ->withTimestamps();
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'grades')
            ->withPivot(['semester_id', 'attendance_score', 'assignment_score', 'midterm_score', 'final_score', 'final_grade', 'letter_grade'])
            ->withTimestamps();
    }

    public function studentClasses()
    {
        return $this->belongsToMany(StudentClass::class, 'course_student_class');
    }

    /**
     * Helper: Get first student class (untuk kompatibilitas)
     * Jika perlu akses singular
     */
    public function studentClass()
    {
        return $this->studentClasses()->first();
    }

    // Helper method untuk auto-assign berdasarkan pattern
    public function assignToClassesByPattern()
    {
        if (empty($this->class_pattern)) {
            return;
        }
        
        // PERBAIKAN: Tambahkan spasi atau boundary untuk exact match
        // Contoh: "S2 PKU " akan match "S2 PKU A", "S2 PKU B", tapi TIDAK match "S2 PKUP"
        $pattern = trim($this->class_pattern);
        
        // Cari kelas yang namanya cocok dengan pattern
        // Gunakan space sebagai boundary
        $classes = StudentClass::where(function($query) use ($pattern) {
            $query->where('name', 'LIKE', $pattern . ' %')  // "S2 PKU A"
                ->orWhere('name', '=', $pattern);          // "S2 PKU" exact
        })->get();
        
        \Log::info('Auto-assign by pattern', [
            'pattern' => $pattern,
            'found_classes' => $classes->pluck('name')->toArray()
        ]);
        
        foreach ($classes as $class) {
            // Attach jika belum terhubung
            $this->studentClasses()->syncWithoutDetaching([$class->id]);
        }
    }
    
    /**
     * Relasi untuk mendapatkan kelas yang terdaftar di semester tertentu
     */
    public function studentClassesInSemester($semesterId)
    {
        return $this->belongsToMany(StudentClass::class, 'enrollments')
            ->wherePivot('semester_id', $semesterId)
            ->wherePivot('status', 'enrolled')
            ->withPivot(['enrollment_date', 'status'])
            ->withTimestamps();
    }
}