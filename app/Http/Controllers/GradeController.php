<?php

namespace App\Http\Controllers;

use App\Models\Year;
use App\Models\Grade;
use App\Models\Course;
use App\Models\Student;
use App\Models\Semester;
use App\Models\Enrollment;
use App\Models\GradeWeight;
use App\Imports\GradeImport;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GradeController extends Controller
{
    /**
     * Display grades management page with course selection
     */
    public function index(Request $request)
    {
        $courses = Course::all();
        $semesters = Semester::orderBy('start_date', 'desc')->get();
        $years = Year::all();
        $studentClasses = StudentClass::with('year')->get(); // Tambahkan data kelas
        
        $selectedCourseId = $request->get('course_id');
        $selectedSemesterId = $request->get('semester_id');
        $selectedYearId = $request->get('year_id');
        $selectedClassId = $request->get('class_id');
        
        $students = collect();
        $grades = collect();
        
        if ($selectedCourseId && $selectedSemesterId) {
            // Query dengan filter kelas jika dipilih
            $studentsQuery = Student::with(['studentClass'])
                ->whereHas('studentClass.enrollments', function ($q) use ($selectedCourseId, $selectedSemesterId) {
                    $q->where('course_id', $selectedCourseId)
                    ->where('semester_id', $selectedSemesterId)
                    ->where('status', 'enrolled');
                });
            
            // Tambahkan filter kelas jika dipilih
            if ($selectedClassId) {
                $studentsQuery->where('student_class_id', $selectedClassId);
            }
            
            $students = $studentsQuery->get();
            
            $grades = Grade::where('course_id', $selectedCourseId)
                ->where('semester_id', $selectedSemesterId)
                ->get()
                ->keyBy('student_id');
        }
        
        $weights = GradeWeight::getCurrentWeights();
        
        return view('grades.index', compact(
            'students', 'courses', 'semesters', 'studentClasses',
            'selectedCourseId', 'selectedSemesterId', 'selectedClassId',
            'grades', 'weights', 'years', 'selectedYearId'
        ));
    }

    /**
     * Store/Update grades for selected course
     * Opsi A: validasi enrollment berdasarkan KELAS siswa (student_class_id)
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'semester_id' => 'required|exists:semesters,id',
            'grades'      => 'required|array',
            'grades.*.attendance_score' => 'nullable|numeric|min:0|max:100',
            'grades.*.assignment_score' => 'nullable|numeric|min:0|max:100',
            'grades.*.midterm_score'    => 'nullable|numeric|min:0|max:100',
            'grades.*.final_score'      => 'nullable|numeric|min:0|max:100',
        ]);

        $weights = GradeWeight::getCurrentWeights();
        $updatedCount = 0;

        // Ambil semua siswa yang dikirim pada payload sekaligus (hemat query)
        $studentIds    = array_keys($request->grades);
        $studentsById  = Student::whereIn('id', $studentIds)
                            ->get(['id', 'student_class_id'])
                            ->keyBy('id');

        foreach ($request->grades as $studentId => $gradeData) {
            $student = $studentsById->get($studentId);
            if (!$student) {
                continue; // student tidak ditemukan
            }

            // VALIDASI: kelas siswa harus ter-enroll pada course & semester ini
            $enrollmentExists = Enrollment::where('student_class_id', $student->student_class_id)
                ->where('course_id', $request->course_id)
                ->where('semester_id', $request->semester_id)
                ->where('status', 'enrolled')
                ->exists();

            if (!$enrollmentExists) {
                continue; // skip jika kelas siswa tidak mengambil MK ini pada semester tsb
            }

            // Filter hanya nilai yang terisi (hindari overwrite null/empty string)
            $filteredData = array_filter($gradeData, function ($value) {
                return $value !== null && $value !== '';
            });

            if (empty($filteredData)) {
                continue;
            }

            // Simpan / update nilai per siswa
            $grade = Grade::updateOrCreate(
                [
                    'student_id'  => $studentId,
                    'course_id'   => $request->course_id,
                    'semester_id' => $request->semester_id,
                ],
                $filteredData
            );

            // Hitung nilai akhir jika semua komponen terisi
            if ($this->allScoresPresent($grade)) {
                $finalGrade = Grade::calculateFinalGrade(
                    $grade->attendance_score,
                    $grade->assignment_score,
                    $grade->midterm_score,
                    $grade->final_score,
                    $weights
                );

                $grade->update([
                    'final_grade'  => $finalGrade,
                    'letter_grade' => Grade::getLetterGrade($finalGrade),
                    'bobot'        => Grade::getBobot($finalGrade),
                ]);
            }

            $updatedCount++;
        }

        return redirect()
            ->route('grades.index', [
                'course_id'   => $request->course_id,
                'semester_id' => $request->semester_id,
            ])
            ->with('success', "Successfully updated grades for {$updatedCount} students!");
    }

    /**
     * Update single grade (AJAX endpoint)
     */
    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'attendance_score' => 'nullable|numeric|min:0|max:100',
            'assignment_score' => 'nullable|numeric|min:0|max:100',
            'midterm_score'    => 'nullable|numeric|min:0|max:100',
            'final_score'      => 'nullable|numeric|min:0|max:100',
        ]);

        $grade->update($request->only([
            'attendance_score',
            'assignment_score',
            'midterm_score',
            'final_score',
        ]));

        if ($this->allScoresPresent($grade)) {
            $weights = GradeWeight::getCurrentWeights();
            $finalGrade = Grade::calculateFinalGrade(
                $grade->attendance_score,
                $grade->assignment_score,
                $grade->midterm_score,
                $grade->final_score,
                $weights
            );

            $grade->update([
                'final_grade'  => $finalGrade,
                'letter_grade' => Grade::getLetterGrade($finalGrade),
                'bobot'        => Grade::getBobot($finalGrade),
            ]);
        }

        return response()->json([
            'success'      => true,
            'final_grade'  => $grade->final_grade,
            'letter_grade' => $grade->letter_grade,
            'bobot'        => $grade->bobot,
        ]);
    }

    /**
     * Bulk update grades (legacy)
     */
    public function bulkUpdate(Request $request)
    {
        $grades  = $request->input('grades');
        $weights = GradeWeight::getCurrentWeights();

        foreach ($grades as $gradeId => $scores) {
            $grade = Grade::find($gradeId);
            if (!$grade) continue;

            $grade->update($scores);

            if ($this->allScoresPresent($grade)) {
                $finalGrade = Grade::calculateFinalGrade(
                    $grade->attendance_score,
                    $grade->assignment_score,
                    $grade->midterm_score,
                    $grade->final_score,
                    $weights
                );

                $grade->update([
                    'final_grade'  => $finalGrade,
                    'letter_grade' => Grade::getLetterGrade($finalGrade),
                    'bobot'        => Grade::getBobot($finalGrade),
                ]);
            }
        }

        return redirect()->route('grades.index')
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    /**
     * Show grades for a specific student (optional)
     */
    public function show($id)
    {
        $student = Student::with(['grades.course'])->findOrFail($id);
        return view('grades.show', compact('student'));
    }

    /**
     * Check if all grade components are present
     */
    private function allScoresPresent($grade)
    {
        return $grade->attendance_score !== null
            && $grade->assignment_score !== null
            && $grade->midterm_score !== null
            && $grade->final_score !== null;
    }

    public function getCoursesByClass(Request $request)
    {
        $classId = $request->get('class_id');
        $search = $request->get('q');
        
        // Validasi class_id
        if (!$classId) {
            return response()->json([]);
        }
        
        $query = Course::select('id', 'name', 'code')
            ->where('student_class_id', $classId);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }
        
        $courses = $query->limit(10)->get();
        
        return response()->json($courses);
    }

    public function getClassesByYear(Request $request)
    {
        $yearId = $request->get('year_id');
        $search = $request->get('q');
        
        if (!$yearId) {
            return response()->json([]);
        }
        
        $query = StudentClass::select('id', 'name')
            ->where('year_id', $yearId);
        
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }
        
        $classes = $query->limit(10)->get();
        
        return response()->json($classes);
    }

    public function importGrades(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new GradeImport;
        Excel::import($import, $request->file('file'));

        if ($import->getErrors()) {
            return back()->with('import_errors', $import->getErrors());
        }

        return redirect()->back()->with('success', 'Data nilai berhasil diimport.');
    }
}
