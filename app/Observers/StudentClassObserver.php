<?php

namespace App\Observers;

use App\Models\Course;
use App\Models\StudentClass;

class StudentClassObserver
{
    /**
     * Handle the StudentClass "created" event.
     * 
     * Saat StudentClass baru dibuat, otomatis attach ke semua courses
     * yang sesuai dengan pattern (optional)
     */
    public function created(StudentClass $studentClass)
    {
        // Cari semua courses dengan pattern yang match
        $matchingCourses = Course::whereNotNull('class_pattern')
            ->get()
            ->filter(function ($course) use ($studentClass) {
                $pattern = trim($course->class_pattern);
                $className = $studentClass->name;
                
                // Precision check: match hanya jika:
                // 1. Nama kelas dimulai dengan pattern + space
                // 2. ATAU nama kelas exactly sama dengan pattern
                $startsWithPattern = strpos($className, $pattern . ' ') === 0;
                $exactMatch = $className === $pattern;
                
                return $startsWithPattern || $exactMatch;
            })
            ->pluck('id');

        if ($matchingCourses->isNotEmpty()) {
            $studentClass->courses()->attach($matchingCourses);
            \Log::info("StudentClass '{$studentClass->name}' auto-linked to " . count($matchingCourses) . " courses");
        } else {
            \Log::info("StudentClass '{$studentClass->name}' has no matching courses");
        }
    }

    /**
     * Handle the StudentClass "updated" event.
     */
    public function updated(StudentClass $studentClass)
    {
        // Optional: sync jika ada perubahan tertentu
    }

    /**
     * Handle the StudentClass "deleted" event.
     */
    public function deleted(StudentClass $studentClass)
    {
        // Detach semua courses saat kelas dihapus
        $studentClass->courses()->detach();
        
        \Log::info("StudentClass {$studentClass->name} unlinked from all courses");
    }

    /**
     * Handle the StudentClass "restored" event.
     */
    public function restored(StudentClass $studentClass)
    {
        //
    }

    /**
     * Handle the StudentClass "force deleted" event.
     */
    public function forceDeleted(StudentClass $studentClass)
    {
        //
    }

    private function extractPattern(string $name): string
    {
        // Extract pattern dari nama kelas
        // Contoh: "X-1" → "X"
        $parts = explode('-', $name);
        return trim($parts[0] ?? $name);
    }
}
