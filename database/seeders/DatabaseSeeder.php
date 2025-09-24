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
            PeriodSeeder::class,
            YearSeeder::class,
            StudentClassSeeder::class, // harus ada sebelum StudentSeeder
            CourseSeeder::class,
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
            ['year_id' => 1, 'student_class_id' => 1, 'nim' => '2025101', 'name' => 'Ahmad Fauzi', 'username' => 'ahmad.fauzi', 'email' => 'ahmad.fauzi@email.com', 'password' => Hash::make('password')],
            ['year_id' => 1, 'student_class_id' => 2, 'nim' => '2025102', 'name' => 'Siti Nurhaliza', 'username' => 'siti.nurhaliza', 'email' => 'siti.nurhaliza@email.com', 'password' => Hash::make('password')],
            ['year_id' => 1, 'student_class_id' => 1, 'nim' => '2025103', 'name' => 'Muhammad Rizki', 'username' => 'muhammad.rizki', 'email' => 'muhammad.rizki@email.com', 'password' => Hash::make('password')],
            ['year_id' => 1, 'student_class_id' => 2, 'nim' => '2025104', 'name' => 'Fatimah Azzahra', 'username' => 'fatimah.azzahra', 'email' => 'fatimah.azzahra@email.com', 'password' => Hash::make('password')],
            ['year_id' => 1, 'student_class_id' => 1, 'nim' => '2025105', 'name' => 'Abdullah Rahman', 'username' => 'abdullah.rahman', 'email' => 'abdullah.rahman@email.com', 'password' => Hash::make('password')],
        ];

        foreach ($students as $studentData) {
            $student = Student::create($studentData);
            Grade::create(['student_id' => $student->id]);
        }


    }
}