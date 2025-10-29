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
        // Lewati baris kosong
        if (empty($row['name']) || empty($row['code'])) {
            return null;
        }

        // Ambil kelas berdasarkan nama (kolom 'kelas' di Excel)
        $class = StudentClass::where('name', trim($row['kelas'] ?? ''))->first();

        // Jika kelas tidak ditemukan, catat error
        if (!$class) {
            $this->errors[] = "Baris kode {$row['code']}: Kelas '{$row['kelas']}' tidak ditemukan di database.";
            return null;
        }

        return new Course([
            'name' => $row['name'],
            'code' => $row['code'],
            'sks' => $row['sks'] ?? null,
            'student_class_id' => $class->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'code' => 'required',
            'kelas' => 'required',
        ];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
