<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Semester;
use App\Models\Year;
use App\Models\StudentClass;
use App\Models\Course;
use App\Models\Grade;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman report dengan filter
     */
    public function index(Request $request)
    {
        $semesters = Semester::orderBy('start_date', 'desc')->get();
        $years = Year::orderBy('name', 'asc')->get();
        $studentClasses = collect();
        $students = collect();

        if ($request->filled(['semester_id', 'year_id', 'student_class_id'])) {
            $students = $this->fetchFilteredStudents($request);
        }

        return view('reports.index', compact('semesters', 'years', 'studentClasses', 'students'));
    }


    /**
     * API untuk mendapatkan student classes berdasarkan year
     */
    public function getStudentClassesByYear($yearId)
    {
        $studentClasses = StudentClass::where('year_id', $yearId)->get();
        return response()->json($studentClasses);
    }

    /**
     * API untuk mendapatkan courses berdasarkan student class dan semester
     */
    public function getCoursesByClassAndSemester(Request $request)
    {
        $studentClassId = $request->student_class_id;
        $semesterId = $request->semester_id;

        // Ambil courses yang terdaftar di kelas tersebut dan semester tersebut
        $courses = Course::where('student_class_id', $studentClassId)
            ->whereHas('enrollments', function($query) use ($semesterId) {
                $query->where('semester_id', $semesterId);
            })
            ->get();

        return response()->json($courses);
    }

    /**
     * Mendapatkan list students berdasarkan filter
     */
    public function getFilteredStudents(Request $request)
    {
        $request->validate([
            'semester_id' => 'required',
            'year_id' => 'required',
            'student_class_id' => 'required',
        ]);

        $semesterId = $request->semester_id;
        $yearId = $request->year_id;
        $studentClassId = $request->student_class_id;

        $students = Student::where('year_id', $yearId)
            ->where('student_class_id', $studentClassId);

        // ✅ tambahkan filter search di dalam query sebelum get()
        if ($request->filled('search')) {
            $search = $request->search;
            $students->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $students = $students->with(['grades' => function ($query) use ($semesterId) {
            $query->where('semester_id', $semesterId)
                ->with('course:id,name,code');
        }])->get();

        return response()->json($students);
    }


    /**
     * Helper untuk mendapatkan students (untuk internal use)
     */
    private function fetchFilteredStudents(Request $request)
    {
        $semesterId = $request->semester_id;
        $yearId = $request->year_id;
        $studentClassId = $request->student_class_id;

        $students = Student::where('year_id', $yearId)
            ->where('student_class_id', $studentClassId)
            ->with(['grades' => function($query) use ($semesterId) {
                $query->where('semester_id', $semesterId)
                    ->with('course:id,code,name');
            }])
            ->get();

        return $students;
    }


    /**
     * Generate PDF berdasarkan filter (list mahasiswa)
     */
    public function printByFilter(Request $request)
    {
        $request->validate([
            'semester_id' => 'required',
            'year_id' => 'required',
            'student_class_id' => 'required',
        ]);

        $students = $this->fetchFilteredStudents($request);
        $semester = Semester::find($request->semester_id);
        $year = Year::find($request->year_id);
        $studentClass = StudentClass::find($request->student_class_id);

        $data = [
            'students' => $students,
            'semester' => $semester,
            'year' => $year,
            'studentClass' => $studentClass,
            'title' => 'Rekap KHS Mahasiswa',
        ];

        $pdf = Pdf::loadView('reports.pdf-khs', $data)->setPaper('A4', 'portrait');
        return $pdf->stream('KHS_' . $studentClass->name . '_' . $semester->name . '.pdf');
    }



    /**
     * Generate PDF detail nilai per mahasiswa
     */
    public function printByStudent(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'semester_id' => 'required',
        ]);

        $student = Student::with(['studentClass', 'year'])->findOrFail($request->student_id);
        $semester = Semester::findOrFail($request->semester_id);

        $grades = Grade::where('student_id', $request->student_id)
            ->where('semester_id', $request->semester_id)
            ->with('course')
            ->get();

        $data = [
            'student' => $student,
            'semester' => $semester,
            'grades' => $grades,
            'title' => 'Kartu Hasil Studi (KHS) - ' . $student->name,
        ];

        $pdf = Pdf::loadView('reports.pdf-student', $data)
            ->setPaper('A4', 'portrait');

        return $pdf->stream('KHS_' . $student->nim . '_' . $semester->name . '.pdf');
    }




}
