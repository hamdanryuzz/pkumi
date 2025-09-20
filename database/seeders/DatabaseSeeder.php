<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Grade;
use App\Models\User;
use App\Models\GradeWeight;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            YearSeeder::class,
            CourseSeeder::class,
            StudentClassSeeder::class, // harus ada sebelum StudentSeeder
        ]);

         User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'), // jangan lupa di-hash!
        ]);

        // Create default grade weights
        GradeWeight::create([
            'attendance_weight' => 10,
            'assignment_weight' => 20,
            'midterm_weight' => 30,
            'final_weight' => 40
        ]);

        // Create sample students
        $students = [
            ['year_id' => 1, 'student_class_id' => 1, 'nim' => '2024001', 'name' => 'Ahmad Fauzi', 'email' => 'ahmad.fauzi@email.com'],
            ['year_id' => 1, 'student_class_id' => 2, 'nim' => '2024002', 'name' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@email.com'],
            ['year_id' => 1, 'student_class_id' => 1, 'nim' => '2024003', 'name' => 'Muhammad Rizki', 'email' => 'muhammad.rizki@email.com'],
            ['year_id' => 1, 'student_class_id' => 2, 'nim' => '2024004', 'name' => 'Fatimah Azzahra', 'email' => 'fatimah.azzahra@email.com'],
            ['year_id' => 1, 'student_class_id' => 1, 'nim' => '2024005', 'name' => 'Abdullah Rahman', 'email' => 'abdullah.rahman@email.com'],
        ];

        foreach ($students as $studentData) {
            $student = Student::create($studentData);
            Grade::create(['student_id' => $student->id]);
        }


    }
}