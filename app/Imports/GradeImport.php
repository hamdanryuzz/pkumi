<?php

namespace App\Imports;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Course;
use App\Models\Semester;
use App\Models\GradeWeight;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;

class GradeImport implements ToModel, WithHeadingRow, SkipsOnFailure, WithValidation
{
    use Importable, SkipsFailures;

    protected $errors = [];

    public function model(array $row)
    {
         $row = array_change_key_case(array_map('trim', $row), CASE_LOWER);
        $student = Student::where('username', trim($row['username'] ?? ''))
            ->orWhere('email', trim($row['email'] ?? ''))
            ->first();

        $course = Course::where('name', trim($row['course_name'] ?? ''))->first();
        $semester = Semester::where('name', trim($row['semester_name'] ?? ''))->first();

        if (!$student || !$course || !$semester) {
            $missing = [];
            if (!$student) $missing[] = "Mahasiswa '{$row['username']}'";
            if (!$course) $missing[] = "Mata kuliah '{$row['course_name']}'";
            if (!$semester) $missing[] = "Semester '{$row['semester_name']}'";
            $this->errors[] = 'Baris dengan mahasiswa ' . ($row['username'] ?? $row['email']) . ' gagal: ' . implode(', ', $missing) . ' tidak ditemukan.';
            return null;
        }

        $weights = GradeWeight::getCurrentWeights();

        $attendance = trim($row['nilai_kehadiran'] ?? '') !== '' ? floatval($row['nilai_kehadiran']) : null;
        $assignment = trim($row['nilai_tugas'] ?? '') !== '' ? floatval($row['nilai_tugas']) : null;
        $midterm = trim($row['nilai_uts'] ?? '') !== '' ? floatval($row['nilai_uts']) : null;
        $final = trim($row['nilai_uas'] ?? '') !== '' ? floatval($row['nilai_uas']) : null;
        $finalGrade = trim($row['nilai_akhir'] ?? '') !== '' ? floatval($row['nilai_akhir']) : null;

        // Jika nilai akhir ada di Excel, pakai itu langsung
        if (!is_null($finalGrade)) {
            $finalGrade = floatval($finalGrade);
        }
        // Jika tidak ada nilai akhir tapi ada komponen nilai, hitung otomatis
        elseif ($attendance !== null || $assignment !== null || $midterm !== null || $final !== null) {
            $finalGrade = Grade::calculateFinalGrade($attendance, $assignment, $midterm, $final, $weights);
        }
        // Kalau semua kosong, finalGrade biarkan null


        $letter = Grade::getLetterGrade($finalGrade);
        $bobot = Grade::getBobot($finalGrade);

        // ✅ Ganti dari `return new Grade(...)` ke updateOrCreate:
        Grade::updateOrCreate(
            [
                'student_id' => $student->id,
                'course_id' => $course->id,
                'semester_id' => $semester->id,
            ],
            [
                'attendance_score' => $attendance,
                'assignment_score' => $assignment,
                'midterm_score' => $midterm,
                'final_score' => $final,
                'final_grade' => $finalGrade,
                'letter_grade' => $letter,
                'bobot' => $bobot,
            ]
        );

        // return null karena updateOrCreate sudah menangani insert/update
        return null;
    }

    public function rules(): array
    {
        return [
            'username' => 'required_without:email',
            'email' => 'required_without:username',
            'course_name' => 'required',
            'semester_name' => 'required',
        ];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
