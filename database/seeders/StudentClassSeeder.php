<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentClass;

class StudentClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            ['year_id' => 1, 'name' => 'A'],
            ['year_id' => 1, 'name' => 'B'],
            ['year_id' => 2, 'name' => 'C'],
            ['year_id' => 2, 'name' => 'D'],
        ];

        foreach ($classes as $class) {
            StudentClass::create($class);
        }
    }
}
