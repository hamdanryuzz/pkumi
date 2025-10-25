<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Year;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;

class StudentsImport implements ToModel, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use Importable, SkipsFailures;

    protected $errors = [];

    public function model(array $row)
    {
        // Skip baris kosong
        if (empty($row['nim']) || empty($row['name'])) {
            return null;
        }

        // Ambil Year berdasarkan nama
        $year = Year::where('name', trim($row['year_name'] ?? ''))->first();

        // Ambil StudentClass berdasarkan nama DAN year_id
        $class = null;
        if ($year) {
            $class = StudentClass::where('name', trim($row['class_name'] ?? ''))
                ->where('year_id', $year->id)
                ->first();
        }

        // Jika data tidak ditemukan, catat error
        if (!$year || !$class) {
            $missing = [];
            if (!$year) $missing[] = "Tahun '{$row['year_name']}'";
            if (!$class) $missing[] = "Kelas '{$row['class_name']}' untuk tahun '{$row['year_name']}'";
            $this->errors[] = "Baris NIM {$row['nim']}: " . implode(' & ', $missing) . " tidak ditemukan di database.";
            return null;
        }

        // Buat record Student baru
        return new Student([
            'nim' => $row['nim'],
            'name' => $row['name'] ?? null,
            'username' => $row['username'] ?? strtolower(str_replace(' ', '.', $row['name'] ?? '')),
            'email' => $row['email'] ?? null,
            'password' => isset($row['password']) 
                ? Hash::make($row['password']) 
                : Hash::make('PkumiStudent11!'),
            'phone' => $row['phone'] ?? null,
            'address' => $row['address'] ?? null,
            'status' => $row['status'] ?? 'active',
            'student_class_id' => $class->id,
            'year_id' => $year->id,

            // Field tambahan
            'gender' => $row['gender'] ?? null,
            'date_of_birth' => $row['date_of_birth'] ?? null,
            'student_job' => $row['student_job'] ?? null,
            'marital_status' => $row['marital_status'] ?? null,
            'program' => $row['program'] ?? null,
            'admission_year' => $row['admission_year'] ?? null,
            'first_semester' => $row['first_semester'] ?? null,
            'origin_of_university' => $row['origin_of_university'] ?? null,
            'initial_study_program' => $row['initial_study_program'] ?? null,
            'graduation_year' => $row['graduation_year'] ?? null,
            'gpa' => $row['gpa'] ?? null,
            'father_name' => $row['father_name'] ?? null,
            'father_last_education' => $row['father_last_education'] ?? null,
            'father_job' => $row['father_job'] ?? null,
            'mother_name' => $row['mother_name'] ?? null,
            'mother_last_education' => $row['mother_last_education'] ?? null,
            'mother_job' => $row['mother_job'] ?? null,
            'street' => $row['street'] ?? null,
            'rt_rw' => $row['rt_rw'] ?? null,
            'village' => $row['village'] ?? null,
            'district' => $row['district'] ?? null,
            'city' => $row['city'] ?? null,
            'province' => $row['province'] ?? null,
            'description' => $row['description'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nim' => 'required',
            'name' => 'required',
            'year_name' => 'required',
            'class_name' => 'required',
        ];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
