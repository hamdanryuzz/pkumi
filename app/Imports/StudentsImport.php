<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Grade;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $student = Student::create([
            'nim' => $row['nim'],
            'name' => $row['nama'] ?? $row['name'],
            'email' => $row['email'] ?? null,
            'phone' => $row['telepon'] ?? $row['phone'] ?? null,
            'status' => $row['status'] ?? 'active',
        ]);

        Grade::create(['student_id' => $student->id]);

        return $student;
    }
}