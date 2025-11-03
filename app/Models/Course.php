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
            \Log::info("Course {$this->name} has no pattern set");
            // Jika pattern kosong, hapus semua koneksi
            $this->studentClasses()->detach();
            return;
        }
        
        $pattern = trim($this->class_pattern);
        
        // Cari kelas dengan precise matching
        $matchingClasses = \App\Models\StudentClass::where(function($query) use ($pattern) {
            // Pattern "S2 PKU" akan match "S2 PKU A", "S2 PKU B" (pattern + space)
            $query->where('name', 'LIKE', $pattern . ' %')
                  ->orWhere('name', '=', $pattern);  // Exact match
        })->get();
        
        \Log::info('Pattern matching assignment', [
            'course' => $this->name,
            'pattern' => $pattern,
            'found_classes' => $matchingClasses->pluck('name')->toArray(),
            'count' => $matchingClasses->count()
        ]);

        // sync():
        // 1. Hapus koneksi lama yang tidak match
        // 2. Tambah koneksi baru yang match
        // 3. Keep koneksi yang sudah ada dan masih match
        if ($matchingClasses->isNotEmpty()) {
            $this->studentClasses()->sync($matchingClasses->pluck('id'));
            \Log::info("✓ Course {$this->name} synced to {$matchingClasses->count()} classes");
        } else {
            // Jika tidak ada match, hapus semua koneksi
            $this->studentClasses()->detach();
            \Log::warning("⚠ Course {$this->name} has no matching classes for pattern: {$pattern}");
        }
    }

    /**
     * Get matching student classes berdasarkan current pattern
     */
    public function getMatchingClasses()
    {
        if (!$this->class_pattern) {
            return collect();
        }

        $pattern = trim($this->class_pattern);
        
        return \App\Models\StudentClass::where(function($query) use ($pattern) {
            $query->where('name', 'LIKE', $pattern . ' %')
                  ->orWhere('name', '=', $pattern);
        })->get();
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