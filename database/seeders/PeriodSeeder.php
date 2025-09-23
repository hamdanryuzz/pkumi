<?php
// database/seeders/PeriodSeeder.php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    public function run()
    {
        Period::create([
            'name' => 'Semester Ganjil 2024/2025',
            'code' => '2024-1',
            'start_date' => '2024-08-01',
            'end_date' => '2024-12-31',
            'enrollment_start_date' => '2024-07-01',
            'enrollment_end_date' => '2024-07-31',
            'status' => 'active'
        ]);

        Period::create([
            'name' => 'Semester Genap 2024/2025',
            'code' => '2024-2',
            'start_date' => '2025-02-01',
            'end_date' => '2025-06-30',
            'enrollment_start_date' => '2025-01-01',
            'enrollment_end_date' => '2025-01-31',
            'status' => 'draft'
        ]);
    }
}
