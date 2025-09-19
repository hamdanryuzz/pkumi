<?php

namespace App\Http\Controllers;

use App\Models\GradeWeight;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeWeightController extends Controller
{
    public function index()
    {
        $weights = GradeWeight::getCurrentWeights();
        $gradingScale = [
            '95 - 100' => 'A+',
            '90 - 94' => 'A',
            '85 - 89' => 'A-',
            '80 - 84' => 'B+',
            '75 - 79' => 'B',
            '70 - 74' => 'B-',
            '< 70' => 'C'
        ];
        return view('grade-weights.index', compact('weights','gradingScale'));
    }

    public function update(Request $request)
    {
        // Check if request is AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $request->validate([
                'attendance_weight' => 'required|numeric|min:0|max:100',
                'assignment_weight' => 'required|numeric|min:0|max:100',
                'midterm_weight' => 'required|numeric|min:0|max:100',
                'final_weight' => 'required|numeric|min:0|max:100',
            ]);

            $totalWeight = $request->attendance_weight + 
                          $request->assignment_weight + 
                          $request->midterm_weight + 
                          $request->final_weight;

            if ($totalWeight != 100) {
                return response()->json(['error' => 'Total bobot harus 100%'], 400);
            }

            $weights = GradeWeight::getCurrentWeights();
            $weights->update($request->only([
                'attendance_weight',
                'assignment_weight',
                'midterm_weight',
                'final_weight'
            ]));

            // Recalculate all final grades
            $this->recalculateAllGrades($weights);

            return response()->json(['success' => 'Bobot nilai berhasil diperbarui.']);
        }

        // Fallback untuk non-AJAX request (tetap ada redirect)
        $request->validate([
            'attendance_weight' => 'required|numeric|min:0|max:100',
            'assignment_weight' => 'required|numeric|min:0|max:100',
            'midterm_weight' => 'required|numeric|min:0|max:100',
            'final_weight' => 'required|numeric|min:0|max:100',
        ]);

        $totalWeight = $request->attendance_weight + 
                      $request->assignment_weight + 
                      $request->midterm_weight + 
                      $request->final_weight;

        if ($totalWeight != 100) {
            return back()->withErrors(['total' => 'Total bobot harus 100%']);
        }

        $weights = GradeWeight::getCurrentWeights();
        $weights->update($request->only([
            'attendance_weight',
            'assignment_weight',
            'midterm_weight',
            'final_weight'
        ]));

        $this->recalculateAllGrades($weights);

        return redirect()->route('grade-weights.index')
            ->with('success', 'Bobot nilai berhasil diperbarui.');
    }

    public function reset(Request $request)
    {
        // Check if request is AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $weights = GradeWeight::getCurrentWeights();
            $weights->update([
                'attendance_weight' => 10,
                'assignment_weight' => 20,
                'midterm_weight' => 30,
                'final_weight' => 40
            ]);

            $this->recalculateAllGrades($weights);

            return response()->json(['success' => 'Bobot nilai berhasil direset ke default.']);
        }

        // Fallback untuk non-AJAX request (tetap ada redirect)
        $weights = GradeWeight::getCurrentWeights();
        $weights->update([
            'attendance_weight' => 10,
            'assignment_weight' => 20,
            'midterm_weight' => 30,
            'final_weight' => 40
        ]);

        $this->recalculateAllGrades($weights);

        return redirect()->route('grade-weights.index')
            ->with('success', 'Bobot nilai berhasil direset ke default.');
    }

    private function recalculateAllGrades($weights)
    {
        $grades = Grade::whereNotNull('attendance_score')
            ->whereNotNull('assignment_score')
            ->whereNotNull('midterm_score')
            ->whereNotNull('final_score')
            ->get();

        foreach ($grades as $grade) {
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