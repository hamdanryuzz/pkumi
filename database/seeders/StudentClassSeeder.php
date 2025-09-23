<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentClass;

class StudentClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            ['year_id' => 1, 'name' => 'S2 PKUP'],
            ['year_id' => 1, 'name' => 'S2 PKU'],
            ['year_id' => 1, 'name' => 'S3 PKU'],

            ['year_id' => 2, 'name' => 'S2 PKUP'],
            ['year_id' => 2, 'name' => 'S2 PKU'],
            ['year_id' => 2, 'name' => 'S3 PKU'],
        ];

        foreach ($classes as $class) {
            StudentClass::create($class);
        }
    }
}
