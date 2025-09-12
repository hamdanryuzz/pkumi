<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Student::select('nim', 'name', 'email', 'phone', 'status')->get();
    }

    public function headings(): array
    {
        return [
            'NIM',
            'Nama',
            'Email',
            'Telepon',
            'Status'
        ];
    }
}