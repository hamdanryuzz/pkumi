<?php
// database/seeders/SemesterSeeder.php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run()
    {
        // Semester Genap 2024/2025 (sedang berjalan di tahun 2025)
        Semester::create([
            'name' => 'Semester Genap 2024/2025',
            'code' => '2025-1',
            'start_date' => '2025-02-01',
            'end_date' => '2025-06-30',
            'enrollment_start_date' => '2025-01-01',
            'enrollment_end_date' => '2025-01-31',
            'status' => 'draft'
        ]);

        // Semester Ganjil 2025/2026 (akan datang setelah Juni 2025)
        Semester::create([
            'name' => 'Semester Ganjil 2025/2026',
            'code' => '2025-2',
            'start_date' => '2025-08-01',
            'end_date' => '2025-12-31',
            'enrollment_start_date' => '2025-09-01',
            'enrollment_end_date' => '2025-09-30',
            'status' => 'active'
        ]);
    }
}
