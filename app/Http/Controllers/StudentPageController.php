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
        
        // Ambil nilai dari tabel grades
        $courses = $enrollments->map(function($enrollment) use ($student) {
            $grade = Grade::where('student_id', $student->id)
                ->where('course_id', $enrollment->course_id)
                ->where('semester_id', $enrollment->semester_id)
                ->first();
            
            return [
                'course_name' => $enrollment->course->name,
                'course_code' => $enrollment->course->code,
                'credits' => $enrollment->course->credits,
                'letter_grade' => $grade->letter_grade ?? '-',
                'final_grade' => $grade->final_grade ?? '-',
                'semester' => $enrollment->semester->name,
                'period' => $enrollment->semester->period->year,
            ];
        });
        
        return view('mahasiswa.grades', compact('periods', 'semesters', 'courses'));
    }
}
