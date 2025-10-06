<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Period;
use App\Models\Semester;
use App\Models\Enrollment;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;

class StudentPageController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        // Ambil data untuk filter dropdown
        $periods = Period::orderBy('code', 'desc')->get();
        $semesters = Semester::when($request->period_id, function($query) use ($request) {
            return $query->where('period_id', $request->period_id);
        })->orderBy('code', 'asc')->get();
        
        // Query untuk mendapatkan mata kuliah berdasarkan filter
        $enrollments = Enrollment::where('student_id', $student->id)
            ->where('status', 'enrolled')
            ->when($request->period_id, function($query) use ($request) {
                return $query->whereHas('semester', function($q) use ($request) {
                    $q->where('period_id', $request->period_id);
                });
            })
            ->when($request->semester_id, function($query) use ($request) {
                return $query->where('semester_id', $request->semester_id);
            })
            ->with(['course', 'semester.period'])
            ->get();
        
        // Ambil nilai dari tabel grades dan hitung SKS/Bobot
        $courses = $enrollments->map(function($enrollment) use ($student) {
            $grade = Grade::where('student_id', $student->id)
                // Pastikan menggunakan ID course dari enrollment
                ->where('course_id', $enrollment->course_id) 
                ->where('semester_id', $enrollment->semester_id)
                ->first();
            
            return [
                'course_name' => $enrollment->course->name,
                'course_code' => $enrollment->course->code,
                'sks' => $enrollment->course->sks,
                'letter_grade' => $grade->letter_grade ?? '-',
                'final_grade' => $grade->final_grade ?? '-',
                'semester' => $enrollment->semester->name,
                // Menggunakan period->name
                'period_name' => $enrollment->semester->period->name, 
            ];
        });

        return view('mahasiswa.grades', compact('periods', 'semesters', 'courses'));
    }
    
    /**
     * Menampilkan halaman profil Mahasiswa yang sedang login.
     * Dibuat untuk memenuhi route 'mahasiswa.profile'.
     */
    public function profile()
    {
        // Data student sudah di-load oleh middleware 'auth:student'
        $student = Auth::guard('student')->user();
        
        // Menggunakan view 'mahasiswa.profile'
        return view('mahasiswa.profile', compact('student'));
    }
}