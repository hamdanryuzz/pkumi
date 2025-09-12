<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GradesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Student::with('grade')->get()->map(function ($student) {
            return [
                'nim' => $student->nim,
                'name' => $student->name,
                'attendance' => $student->grade->attendance_score ?? '-',
                'assignment' => $student->grade->assignment_score ?? '-',
                'midterm' => $student->grade->midterm_score ?? '-',
                'final' => $student->grade->final_score ?? '-',
                'final_grade' => $student->grade->final_grade ?? '-',
                'letter_grade' => $student->grade->letter_grade ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NIM',
            'Nama',
            'Presensi',
            'Tugas',
            'UTS',
            'UAS',
            'Nilai Akhir',
            'Huruf'
        ];
    }
}