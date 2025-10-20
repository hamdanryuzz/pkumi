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
        $semesters = Semester::all();
        $years = Year::all();
        $studentClasses = collect();
        $courses = collect();
        $students = collect();

        // Jika ada filter yang dipilih
        if ($request->filled(['semester_id', 'year_id', 'student_class_id', 'course_id'])) {
            $students = $this->fetchFilteredStudents($request); // Ubah ini
        }

        return view('reports.index', compact('semesters', 'years', 'studentClasses', 'courses', 'students'));
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
            'course_id' => 'required',
        ]);

        $semesterId = $request->semester_id;
        $yearId = $request->year_id;
        $studentClassId = $request->student_class_id;
        $courseId = $request->course_id;

        // Ambil mahasiswa berdasarkan filter dengan grades mereka
        $students = Student::where('year_id', $yearId)
            ->where('student_class_id', $studentClassId)
            ->whereHas('grades', function($query) use ($courseId, $semesterId) {
                $query->where('course_id', $courseId)
                    ->where('semester_id', $semesterId);
            })
            ->with(['grades' => function($query) use ($courseId, $semesterId) {
                $query->where('course_id', $courseId)
                    ->where('semester_id', $semesterId);
            }])
            ->get();

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
        $courseId = $request->course_id;

        $students = Student::where('year_id', $yearId)
            ->where('student_class_id', $studentClassId)
            ->whereHas('grades', function($query) use ($courseId, $semesterId) {
                $query->where('course_id', $courseId)
                    ->where('semester_id', $semesterId);
            })
            ->with(['grades' => function($query) use ($courseId, $semesterId) {
                $query->where('course_id', $courseId)
                    ->where('semester_id', $semesterId);
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
            'course_id' => 'required',
        ]);

        $students = $this->fetchFilteredStudents($request);
        
        // Ambil data filter untuk ditampilkan di PDF
        $semester = Semester::find($request->semester_id);
        $year = Year::find($request->year_id);
        $studentClass = StudentClass::find($request->student_class_id);
        $course = Course::find($request->course_id);

        $data = [
            'students' => $students,
            'semester' => $semester,
            'year' => $year,
            'studentClass' => $studentClass,
            'course' => $course,
            'title' => 'Laporan Nilai Mahasiswa'
        ];

        $pdf = Pdf::loadView('reports.pdf-filter', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Laporan_Nilai_' . $course->code . '_' . $semester->name . '.pdf';
        return $pdf->stream($filename);
    }

    /**
     * Generate PDF detail nilai per mahasiswa
     */
    public function printByStudent(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'semester_id' => 'required',
            'course_id' => 'required',
        ]);

        $student = Student::with(['studentClass', 'year'])->findOrFail($request->student_id);
        $semester = Semester::find($request->semester_id);
        $course = Course::find($request->course_id);

        // Ambil grade detail mahasiswa
        $grade = Grade::where('student_id', $request->student_id)
            ->where('semester_id', $request->semester_id)
            ->where('course_id', $request->course_id)
            ->with(['course', 'semester'])
            ->firstOrFail();

        $data = [
            'student' => $student,
            'grade' => $grade,
            'semester' => $semester,
            'course' => $course,
            'title' => 'Rincian Nilai Mahasiswa'
        ];

        $pdf = Pdf::loadView('reports.pdf-student', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Nilai_' . $student->nim . '_' . $course->code . '.pdf';
        return $pdf->stream($filename);
    }
}
