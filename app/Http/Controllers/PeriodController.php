<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PeriodController extends Controller
{
    /**
     * Display a listing of periods
     */
    public function index()
    {
        $periods = Period::orderBy('start_date', 'desc')->paginate(10);
        
        return view('periods.index', compact('periods'));
    }

    /**
     * Show the form for creating a new period
     */
    public function create()
    {
        return view('periods.create');
    }

    /**
     * Store a newly created period
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:periods,code',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'enrollment_start_date' => 'required|date|before_or_equal:start_date',
            'enrollment_end_date' => 'required|date|after:enrollment_start_date|before_or_equal:start_date',
            'status' => ['required', Rule::in(['draft', 'active', 'completed'])]
        ], [
            'end_date.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
            'enrollment_start_date.before_or_equal' => 'Tanggal mulai pendaftaran harus sebelum atau sama dengan tanggal mulai periode.',
            'enrollment_end_date.after' => 'Tanggal berakhir pendaftaran harus setelah tanggal mulai pendaftaran.',
            'enrollment_end_date.before_or_equal' => 'Tanggal berakhir pendaftaran harus sebelum atau sama dengan tanggal mulai periode.'
        ]);

        // Check if there's already an active period when trying to create another active period
        if ($validated['status'] === 'active') {
            $existingActive = Period::where('status', 'active')->exists();
            if ($existingActive) {
                return back()->withErrors(['status' => 'Hanya bisa ada satu periode aktif dalam satu waktu.'])->withInput();
            }
        }

        Period::create($validated);

        return redirect()->route('periods.index')
            ->with('success', 'Period berhasil dibuat!');
    }

    /**
     * Display the specified period
     */
    public function show(Period $period)
    {
        $period->load(['enrollments.student', 'enrollments.course']);
        
        $statistics = [
            'total_enrollments' => $period->enrollments()->count(),
            'active_enrollments' => $period->enrollments()->where('status', 'enrolled')->count(),
            'total_courses' => $period->enrollments()->distinct('course_id')->count(),
            'total_students' => $period->enrollments()->distinct('student_id')->count()
        ];

        return view('periods.show', compact('period', 'statistics'));
    }

    /**
     * Show the form for editing the specified period
     */
    public function edit(Period $period)
    {
        return view('periods.edit', compact('period'));
    }

    /**
     * Update the specified period
     */
    public function update(Request $request, Period $period)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('periods', 'code')->ignore($period->id)],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'enrollment_start_date' => 'required|date|before_or_equal:start_date',
            'enrollment_end_date' => 'required|date|after:enrollment_start_date|before_or_equal:start_date',
            'status' => ['required', Rule::in(['draft', 'active', 'completed'])]
        ], [
            'end_date.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
            'enrollment_start_date.before_or_equal' => 'Tanggal mulai pendaftaran harus sebelum atau sama dengan tanggal mulai periode.',
            'enrollment_end_date.after' => 'Tanggal berakhir pendaftaran harus setelah tanggal mulai pendaftaran.',
            'enrollment_end_date.before_or_equal' => 'Tanggal berakhir pendaftaran harus sebelum atau sama dengan tanggal mulai periode.'
        ]);

        // Check if there's already an active period when trying to update status to active
        if ($validated['status'] === 'active' && $period->status !== 'active') {
            $existingActive = Period::where('status', 'active')->where('id', '!=', $period->id)->exists();
            if ($existingActive) {
                return back()->withErrors(['status' => 'Hanya bisa ada satu periode aktif dalam satu waktu.'])->withInput();
            }
        }

        $period->update($validated);

        return redirect()->route('periods.index')
            ->with('success', 'Period berhasil diupdate!');
    }

    /**
     * Remove the specified period
     */
    public function destroy(Period $period)
    {
        // Check if period has enrollments
        if ($period->enrollments()->exists()) {
            return back()->withErrors(['delete' => 'Tidak dapat menghapus period yang memiliki data pendaftaran.']);
        }

        $period->delete();

        return redirect()->route('periods.index')
            ->with('success', 'Period berhasil dihapus!');
    }

    /**
     * Activate a period (deactivate others)
     */
    public function activate(Period $period)
    {
        // Deactivate all other periods
        Period::where('status', 'active')->update(['status' => 'draft']);
        
        // Activate current period
        $period->update(['status' => 'active']);

        return back()->with('success', "Period {$period->name} berhasil diaktifkan!");
    }

    /**
     * Get courses available for a specific period
     */
    public function getCourses(Period $period)
    {
        $courses = Course::all();
        
        return response()->json([
            'period' => $period,
            'courses' => $courses
        ]);
    }

    /**
     * Get enrollment statistics for a period
     */
    public function getEnrollmentStats(Period $period)
    {
        $stats = [
            'total_enrollments' => $period->enrollments()->count(),
            'active_enrollments' => $period->enrollments()->where('status', 'enrolled')->count(),
            'dropped_enrollments' => $period->enrollments()->where('status', 'dropped')->count(),
            'completed_enrollments' => $period->enrollments()->where('status', 'completed')->count(),
            'courses_offered' => $period->enrollments()->distinct('course_id')->count(),
            'students_enrolled' => $period->enrollments()->distinct('student_id')->count()
        ];

        return response()->json($stats);
    }
}
