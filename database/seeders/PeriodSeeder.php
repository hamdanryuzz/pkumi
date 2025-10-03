<?php

namespace Database\Seeders;

use App\Models\Period;
use App\Models\Semester;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Period 2023/2024 - Completed
        Period::create([
            'name' => 'Tahun Ajaran 2023/2024',
            'code' => '2023-2024',
            'status' => 'completed',
        ]);

        // Period 2024/2025 - Active
        Period::create([
            'name' => 'Tahun Ajaran 2024/2025',
            'code' => '2024-2025',
            'status' => 'draft',
        ]);

        // Period 2025/2026 - Draft
        Period::create([
            'name' => 'Tahun Ajaran 2025/2026',
            'code' => '2025-2026',
            'status' => 'active',
        ]);
    }
}
