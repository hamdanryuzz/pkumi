<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $courses = [
            ['name' => 'Pemrograman Web', 'code' => 'CS101'],
            ['name' => 'Basis Data', 'code' => 'CS102'],
            ['name' => 'Jaringan Komputer', 'code' => 'CS103'],
            ['name' => 'Kecerdasan Buatan', 'code' => 'CS104'],
            ['name' => 'Algoritma & Struktur Data', 'code' => 'CS105'],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
