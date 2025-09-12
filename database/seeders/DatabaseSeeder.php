<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Grade;
use App\Models\GradeWeight;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create default grade weights
        GradeWeight::create([
            'attendance_weight' => 10,
            'assignment_weight' => 20,
            'midterm_weight' => 30,
            'final_weight' => 40
        ]);

        // Create sample students
        $students = [
            ['nim' => '2024001', 'name' => 'Ahmad Fauzi', 'email' => 'ahmad.fauzi@email.com'],
            ['nim' => '2024002', 'name' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@email.com'],
            ['nim' => '2024003', 'name' => 'Muhammad Rizki', 'email' => 'muhammad.rizki@email.com'],
            ['nim' => '2024004', 'name' => 'Fatimah Azzahra', 'email' => 'fatimah.azzahra@email.com'],
            ['nim' => '2024005', 'name' => 'Abdullah Rahman', 'email' => 'abdullah.rahman@email.com'],
        ];

        foreach ($students as $studentData) {
            $student = Student::create($studentData);
            Grade::create(['student_id' => $student->id]);
        }
    }
}