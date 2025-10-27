<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Period;
use App\Models\Year;
use App\Models\StudentClass;
use App\Models\Student;

class DummyLoginSeeder extends Seeder
{
    public function run()
    {
        // 1. Bikin Period
        $period = Period::create([
            'name' => '2024/2025',
            'code' => '20242025',
            'status' => 'active',
        ]);

        // 2. Bikin Year
        $year = Year::create([
            'period_id' => $period->id,
            'name' => '2024/2025 Ganjil',
        ]);

        // 3. Bikin Student Class
        $studentClass = StudentClass::create([
            'year_id' => $year->id,
            'name' => 'Kelas A',
        ]);

        // 4. Bikin Mahasiswa untuk Login
        Student::create([
            'student_class_id' => $studentClass->id,
            'year_id' => $year->id,
            'nim' => '2024001',
            'name' => 'Mahasiswa Test',
            'username' => 'mahasiswa',
            'password' => Hash::make('password'),
            'email' => 'mahasiswa@test.com',
            'phone' => '081234567890',
            'gender' => 'Laki-Laki',
            'status' => 'active',
            'program' => 'S1 Informatika',
            'admission_year' => '2024',
        ]);
    }
}