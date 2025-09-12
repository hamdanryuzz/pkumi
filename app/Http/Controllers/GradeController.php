<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use App\Models\GradeWeight;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $students = Student::with('grade')->get();
        $weights = GradeWeight::getCurrentWeights();
        
        return view('grades.index', compact('students', 'weights'));
    }

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
        if ($grade->attendance_score !== null && 
            $grade->assignment_score !== null && 
            $grade->midterm_score !== null && 
            $grade->final_score !== null) {
            
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

        return response()->json(['success' => true]);
    }

    public function bulkUpdate(Request $request)
    {
        $grades = $request->input('grades');
        $weights = GradeWeight::getCurrentWeights();

        foreach ($grades as $gradeId => $scores) {
            $grade = Grade::find($gradeId);
            if ($grade) {
                $grade->update($scores);

                // Calculate final grade if all scores are present
                if (isset($scores['attendance_score']) && 
                    isset($scores['assignment_score']) && 
                    isset($scores['midterm_score']) && 
                    isset($scores['final_score'])) {
                    
                    $finalGrade = Grade::calculateFinalGrade(
                        $scores['attendance_score'],
                        $scores['assignment_score'],
                        $scores['midterm_score'],
                        $scores['final_score'],
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
}