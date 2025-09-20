<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\Grade;
use App\Models\GradeWeight;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display grades management page with course selection
     */
    public function index(Request $request)
    {
        // Ambil semua courses untuk dropdown
        $courses = Course::all();
        
        // Ambil course yang dipilih (jika ada)
        $selectedCourseId = $request->get('course_id');
        
        // Ambil semua students
        $students = Student::all();
        
        // Ambil weights untuk perhitungan
        $weights = GradeWeight::getCurrentWeights();
        
        // Ambil grades untuk course yang dipilih
        $grades = collect(); // empty collection sebagai default
        if ($selectedCourseId) {
            $grades = Grade::where('course_id', $selectedCourseId)
                          ->get()
                          ->keyBy('student_id'); // index by student_id untuk akses mudah
        }
        
        return view('grades.index', compact('students', 'courses', 'selectedCourseId', 'grades', 'weights'));
    }

    /**
     * Store/Update grades for selected course
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'grades' => 'required|array',
            'grades.*.attendance_score' => 'nullable|numeric|min:0|max:100',
            'grades.*.assignment_score' => 'nullable|numeric|min:0|max:100',
            'grades.*.midterm_score' => 'nullable|numeric|min:0|max:100',
            'grades.*.final_score' => 'nullable|numeric|min:0|max:100',
        ]);

        $weights = GradeWeight::getCurrentWeights();
        $updatedCount = 0;
        
        foreach ($request->grades as $studentId => $gradeData) {
            // Filter out null/empty values
            $filteredData = array_filter($gradeData, function($value) {
                return $value !== null && $value !== '';
            });
            
            // Skip if no data to save
            if (empty($filteredData)) {
                continue;
            }
            
            // Use updateOrCreate for insert or update logic
            $grade = Grade::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'course_id' => $request->course_id
                ],
                $filteredData
            );
            
            // Calculate final grade if all components are present
            if ($this->allScoresPresent($grade)) {
                $finalGrade = Grade::calculateFinalGrade(
                    $grade->attendance_score,
                    $grade->assignment_score,
                    $grade->midterm_score,
                    $grade->final_score,
                    $weights
                );
                
                $grade->update([
                    'final_grade' => $finalGrade,
                    'letter_grade' => Grade::getLetterGrade($finalGrade)
                ]);
            }
            
            $updatedCount++;
        }
        
        return redirect()
            ->route('grades.index', ['course_id' => $request->course_id])
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
            'midterm_score' => 'nullable|numeric|min:0|max:100',
            'final_score' => 'nullable|numeric|min:0|max:100',
        ]);

        $grade->update($request->only([
            'attendance_score',
            'assignment_score',
            'midterm_score',
            'final_score'
        ]));

        // Calculate final grade if all scores are present
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
                'final_grade' => $finalGrade,
                'letter_grade' => Grade::getLetterGrade($finalGrade)
            ]);
        }

        return response()->json([
            'success' => true,
            'final_grade' => $grade->final_grade,
            'letter_grade' => $grade->letter_grade
        ]);
    }

    /**
     * Bulk update grades (legacy method - kept for compatibility)
     */
    public function bulkUpdate(Request $request)
    {
        $grades = $request->input('grades');
        $weights = GradeWeight::getCurrentWeights();

        foreach ($grades as $gradeId => $scores) {
            $grade = Grade::find($gradeId);
            if ($grade) {
                $grade->update($scores);

                // Calculate final grade if all scores are present
                if ($this->allScoresPresent($grade)) {
                    $finalGrade = Grade::calculateFinalGrade(
                        $grade->attendance_score,
                        $grade->assignment_score,
                        $grade->midterm_score,
                        $grade->final_score,
                        $weights
                    );

                    $grade->update([
                        'final_grade' => $finalGrade,
                        'letter_grade' => Grade::getLetterGrade($finalGrade)
                    ]);
                }
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
        return $grade->attendance_score !== null &&
               $grade->assignment_score !== null &&
               $grade->midterm_score !== null &&
               $grade->final_score !== null;
    }
}
