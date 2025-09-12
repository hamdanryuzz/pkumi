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
        return view('grade-weights.index', compact('weights'));
    }

    public function update(Request $request)
    {
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

        // Recalculate all final grades
        $this->recalculateAllGrades($weights);

        return redirect()->route('grade-weights.index')
            ->with('success', 'Bobot nilai berhasil diperbarui.');
    }

    public function reset()
    {
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