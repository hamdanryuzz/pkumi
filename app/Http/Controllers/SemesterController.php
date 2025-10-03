<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class SemesterController extends Controller
{
    /**
     * Display a listing of semester
     */
    public function index()
    {
        $semesters = Semester::orderBy('start_date', 'desc')->paginate(10);
        
        return view('semester.index', compact('semesters'));
    }

    /**
     * Show the form for creating a new semester
     */
    public function create()
    {
        return view('semester.create');
    }

    /**
     * Store a newly created semester
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:semesters,code',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'enrollment_start_date' => 'required|date|before_or_equal:start_date',
            'enrollment_end_date' => 'required|date|after:enrollment_start_date|before_or_equal:start_date',
            'status' => ['required', Rule::in(['draft', 'active', 'completed'])]
        ], [
            'end_date.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
            'enrollment_start_date.before_or_equal' => 'Tanggal mulai pendaftaran harus sebelum atau sama dengan tanggal mulai semestere.',
            'enrollment_end_date.after' => 'Tanggal berakhir pendaftaran harus setelah tanggal mulai pendaftaran.',
            'enrollment_end_date.before_or_equal' => 'Tanggal berakhir pendaftaran harus sebelum atau sama dengan tanggal mulai semestere.'
        ]);

        // Check if there's already an active semester when trying to create another active semester
        if ($validated['status'] === 'active') {
            $existingActive = Semester::where('status', 'active')->exists();
            if ($existingActive) {
                return back()->withErrors(['status' => 'Hanya bisa ada satu semestere aktif dalam satu waktu.'])->withInput();
            }
        }

        Semester::create($validated);

        return redirect()->route('semester.index')
            ->with('success', 'Semester berhasil dibuat!');
    }

    /**
     * Display the specified semester
     */
    public function show(Semester $semester)
    {
        $semester->load(['enrollments.student', 'enrollments.course']);
        
        $statistics = [
            'total_enrollments' => $semester->enrollments()->count(),
            'active_enrollments' => $semester->enrollments()->where('status', 'enrolled')->count(),
            'total_courses' => $semester->enrollments()->distinct('course_id')->count(),
            'total_students' => $semester->enrollments()->distinct('student_id')->count()
        ];

        return view('semester.show', compact('semester', 'statistics'));
    }

    /**
     * Show the form for editing the specified semester
     */
    public function edit(Semester $semester)
    {
        return view('semester.edit', compact('semester'));
    }

    /**
     * Update the specified semester
     */
    public function update(Request $request, Semester $semester)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('semesters', 'code')->ignore($semester->id)],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'enrollment_start_date' => 'required|date|before_or_equal:start_date',
            'enrollment_end_date' => 'required|date|after:enrollment_start_date|before_or_equal:start_date',
            'status' => ['required', Rule::in(['draft', 'active', 'completed'])]
        ], [
            'end_date.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
            'enrollment_start_date.before_or_equal' => 'Tanggal mulai pendaftaran harus sebelum atau sama dengan tanggal mulai semestere.',
            'enrollment_end_date.after' => 'Tanggal berakhir pendaftaran harus setelah tanggal mulai pendaftaran.',
            'enrollment_end_date.before_or_equal' => 'Tanggal berakhir pendaftaran harus sebelum atau sama dengan tanggal mulai semestere.'
        ]);

        // Check if there's already an active semester when trying to update status to active
        if ($validated['status'] === 'active' && $semester->status !== 'active') {
            $existingActive = Semester::where('status', 'active')->where('id', '!=', $semester->id)->exists();
            if ($existingActive) {
                return back()->withErrors(['status' => 'Hanya bisa ada satu semestere aktif dalam satu waktu.'])->withInput();
            }
        }

        $semester->update($validated);

        return redirect()->route('semester.index')
            ->with('success', 'semester berhasil diupdate!');
    }

    /**
     * Remove the specified semester
     */
    public function destroy(Semester $semester)
    {
        // Check if semester has enrollments
        if ($semester->enrollments()->exists()) {
            return back()->withErrors(['delete' => 'Tidak dapat menghapus semester yang memiliki data pendaftaran.']);
        }

        $semester->delete();

        return redirect()->route('semester.index')
            ->with('success', 'semester berhasil dihapus!');
    }

    /**
     * Activate a semester (deactivate others)
     */
    public function activate(Semester $semester)
    {
        // Deactivate all other semester
        Semester::where('status', 'active')->update(['status' => 'draft']);
        
        // Activate current semester
        $semester->update(['status' => 'active']);

        return back()->with('success', "semester {$semester->name} berhasil diaktifkan!");
    }

    /**
     * Get courses available for a specific semester
     */
    public function getCourses(Semester $semester)
    {
        $courses = Course::all();
        
        return response()->json([
            'semester' => $semester,
            'courses' => $courses
        ]);
    }

    /**
     * Get enrollment statistics for a semester
     */
    public function getEnrollmentStats(Semester $semester)
    {
        $stats = [
            'total_enrollments' => $semester->enrollments()->count(),
            'active_enrollments' => $semester->enrollments()->where('status', 'enrolled')->count(),
            'dropped_enrollments' => $semester->enrollments()->where('status', 'dropped')->count(),
            'completed_enrollments' => $semester->enrollments()->where('status', 'completed')->count(),
            'courses_offered' => $semester->enrollments()->distinct('course_id')->count(),
            'students_enrolled' => $semester->enrollments()->distinct('student_id')->count()
        ];

        return response()->json($stats);
    }
}
