<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Year;

class YearSeeder extends Seeder
{
    public function run(): void
    {
        $years = [
            ['name' => '2023/2024'],
            ['name' => '2024/2025'],
            ['name' => '2025/2026'],
        ];

        foreach ($years as $year) {
            Year::create($year);
        }
    }
}
