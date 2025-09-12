<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'active')->count();
        $gradesEntered = Grade::whereNotNull('final_grade')->count();
        $averageGrade = Grade::whereNotNull('final_grade')->avg('final_grade');
        
        $gradeDistribution = Grade::whereNotNull('letter_grade')
            ->selectRaw('letter_grade, COUNT(*) as count')
            ->groupBy('letter_grade')
            ->get();

        return view('dashboard', compact(
            'totalStudents',
            'activeStudents', 
            'gradesEntered',
            'averageGrade',
            'gradeDistribution'
        ));
    }
}