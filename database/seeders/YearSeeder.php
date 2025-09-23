<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Year;

class YearSeeder extends Seeder
{
    public function run(): void
    {
        $years = [
            ['name' => '2025-1'],
            ['name' => '2025-2'],
        ];

        foreach ($years as $year) {
            Year::create($year);
        }
    }
}
