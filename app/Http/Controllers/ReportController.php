<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use App\Models\GradeWeight;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GradesExport;

class ReportController extends Controller
{
    public function index()
    {
        // Ambil semua data mahasiswa beserta grade
        $students = Student::with('grades')->get();
        
        // Ambil bobot nilai saat ini
        $weights = GradeWeight::getCurrentWeights();

        // Hitung variabel statistik yang dibutuhkan di view
        // Pastikan variabel ini ada untuk menghindari error
        $completedGrades = $students->filter(fn($s) => $s->grade && $s->grade->final_grade);
        $average = $completedGrades->avg(fn($s) => $s->grade->final_grade);
        $highest = $completedGrades->max(fn($s) => $s->grade->final_grade);
        $lowest = $completedGrades->min(fn($s) => $s->grade->final_grade);

        // Kirim semua variabel yang dibutuhkan ke view
        return view('reports.index', compact('students', 'weights', 'average', 'highest', 'lowest'));
    }

    public function exportPdf()
    {
        $students = Student::with('grade')->get();
        $weights = GradeWeight::getCurrentWeights();
        
        $pdf = Pdf::loadView('reports.pdf', compact('students', 'weights'));
        return $pdf->download('laporan-nilai-pkumi.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new GradesExport, 'laporan-nilai-pkumi.xlsx');
    }

    public function studentCard($id)
    {
        $student = Student::with('grade')->findOrFail($id);
        $weights = GradeWeight::getCurrentWeights();
        
        $pdf = Pdf::loadView('reports.student-card', compact('student', 'weights'));
        return $pdf->download('kartu-nilai-' . $student->nim . '.pdf');
    }
}