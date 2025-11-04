<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;

class CoursesImport implements ToModel, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use Importable, SkipsFailures;

    protected $errors = [];

    public function model(array $row)
    {
        // Buat course dulu
        $course = Course::create([
            'name' => $row['name'],
            'code' => $row['code'],
            'sks' => $row['sks'] ?? null,
            'class_pattern' => $row['class_pattern'] ?? null,
        ]);

        // Auto-assign ke student classes berdasarkan pattern
        if (!empty($course->class_pattern)) {
            $course->assignToClassesByPattern();
            
            // Log jika tidak ada kelas yang match
            if ($course->studentClasses()->count() == 0) {
                $this->errors[] = "Baris kode {$row['code']}: Pattern '{$course->class_pattern}' tidak menemukan kelas yang cocok.";
            }
        }

        return $course;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'code' => 'required|unique:courses,code',
            'class_pattern' => 'nullable|in:S2 PKU,S2 PKUP,S3 PKU',
            'sks' => 'nullable|integer|min:1|max:6',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'code.unique' => 'Kode mata kuliah :input sudah ada di database.',
            'class_pattern.in' => 'Pattern kelas harus salah satu dari: S2 PKU, S2 PKUP, S3 PKU',
        ];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
