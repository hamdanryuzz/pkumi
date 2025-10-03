<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $courses = [
            ['name' => 'Pemrograman Web', 'code' => 'CS101', 'student_class_id' => 1, 'sks' => 3],
            ['name' => 'Basis Data', 'code' => 'CS102', 'student_class_id' => 1, 'sks' => 2],
            ['name' => 'Jaringan Komputer', 'code' => 'CS103', 'student_class_id' => 2, 'sks' => 3],
            ['name' => 'Kecerdasan Buatan', 'code' => 'CS104', 'student_class_id' => 2, 'sks' => 2],
            ['name' => 'Algoritma & Struktur Data', 'code' => 'CS105', 'student_class_id' => 3, 'sks' => 3],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
