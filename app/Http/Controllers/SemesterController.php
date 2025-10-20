<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\Period;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SemesterController extends Controller
{
    /**
     * Display a listing of semesters
     */
    public function index(Request $request)
    {
        $query = Semester::with('period');
        
        // Filter by period
        if ($request->filled('period_id')) {
            $query->where('period_id', $request->period_id);
        }
        
        // Search by name or code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhereHas('period', function($periodQuery) use ($search) {
                    $periodQuery->where('name', 'like', "%{$search}%");
                });
            });
        }
        
        $semesters = $query->latest()->paginate(10);
        $periods = Period::all();
        
        return view('semesters.index', compact('semesters', 'periods'));
    }

    /**
     * Show the form for creating a new semester
     */
    public function create()
    {
        $periods = Period::all();
        return view('semesters.create', compact('periods'));
    }

    /**
     * Store a newly created semester
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'period_id' => 'required|exists:periods,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:semesters,code',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'enrollment_start_date' => 'required|date|before_or_equal:start_date',
            'enrollment_end_date' => 'required|date|after:enrollment_start_date|before_or_equal:start_date',
            'status' => ['required', Rule::in(['draft', 'active', 'completed'])]
        ], [
            'period_id.required' => 'Period harus dipilih.',
            'period_id.exists' => 'Period yang dipilih tidak valid.',
            'end_date.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
            'enrollment_start_date.before_or_equal' => 'Tanggal mulai pendaftaran harus sebelum atau sama dengan tanggal mulai semester.',
            'enrollment_end_date.after' => 'Tanggal berakhir pendaftaran harus setelah tanggal mulai pendaftaran.',
            'enrollment_end_date.before_or_equal' => 'Tanggal berakhir pendaftaran harus sebelum atau sama dengan tanggal mulai semester.'
        ]);

        // Check if there's already an active semester when trying to create another active semester
        if ($validated['status'] === 'active') {
            $existingActive = Semester::where('status', 'active')->exists();
            if ($existingActive) {
                return back()->withErrors(['status' => 'Hanya bisa ada satu semester aktif dalam satu waktu.'])->withInput();
            }
        }

        Semester::create($validated);

        return redirect()->route('semesters.index')
            ->with('success', 'Semester berhasil dibuat!');
    }

    /**
     * Display the specified semester
     */
    public function show(Semester $semester)
    {
        $semester->load(['period', 'enrollments.studentClass' => fn($q) => $q->withCount('students'), 'enrollments.course']);

        // Hitung total mahasiswa via Student -> studentClass.enrollments (bukan dari enrollments.student_id)
        $totalStudents = \App\Models\Student::whereHas('studentClass.enrollments', function ($q) use ($semester) {
            $q->where('semester_id', $semester->id)
            ->where('status', 'enrolled');
        })->count();

        $statistics = [
            'total_enrollments'   => $semester->enrollments()->count(),
            'active_enrollments'  => $semester->enrollments()->where('status', 'enrolled')->count(),
            'total_courses'       => $semester->enrollments()->distinct()->count('course_id'),
            'total_students'      => $totalStudents, // 
        ];

        return view('semesters.show', compact('semester', 'statistics'));
    }

    /**
     * Show the form for editing the specified semester
     */
    public function edit(Semester $semester)
    {
        $periods = Period::all();
        return view('semesters.edit', compact('semester', 'periods'));
    }

    /**
     * Update the specified semester
     */
    public function update(Request $request, Semester $semester)
    {
        $validated = $request->validate([
            'period_id' => 'required|exists:periods,id',
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('semesters', 'code')->ignore($semester->id)],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'enrollment_start_date' => 'required|date|before_or_equal:start_date',
            'enrollment_end_date' => 'required|date|after:enrollment_start_date|before_or_equal:start_date',
            'status' => ['required', Rule::in(['draft', 'active', 'completed'])]
        ], [
            'period_id.required' => 'Period harus dipilih.',
            'period_id.exists' => 'Period yang dipilih tidak valid.',
            'end_date.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
            'enrollment_start_date.before_or_equal' => 'Tanggal mulai pendaftaran harus sebelum atau sama dengan tanggal mulai semester.',
            'enrollment_end_date.after' => 'Tanggal berakhir pendaftaran harus setelah tanggal mulai pendaftaran.',
            'enrollment_end_date.before_or_equal' => 'Tanggal berakhir pendaftaran harus sebelum atau sama dengan tanggal mulai semester.'
        ]);

        // Check if there's already an active semester when trying to update status to active
        if ($validated['status'] === 'active' && $semester->status !== 'active') {
            $existingActive = Semester::where('status', 'active')->where('id', '!=', $semester->id)->exists();
            if ($existingActive) {
                return back()->withErrors(['status' => 'Hanya bisa ada satu semester aktif dalam satu waktu.'])->withInput();
            }
        }

        $semester->update($validated);

        return redirect()->route('semesters.index')
            ->with('success', 'Semester berhasil diupdate!');
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

        return redirect()->route('semesters.index')
            ->with('success', 'Semester berhasil dihapus!');
    }

    /**
     * Activate a semester (deactivate others)
     */
    public function activate(Semester $semester)
    {
        // Deactivate all other semesters
        Semester::where('status', 'active')->update(['status' => 'draft']);

        // Activate current semester
        $semester->update(['status' => 'active']);

        return back()->with('success', "Semester {$semester->name} berhasil diaktifkan!");
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
        $studentsEnrolled = \App\Models\Student::whereHas('studentClass.enrollments', function ($q) use ($semester) {
            $q->where('semester_id', $semester->id)
            ->where('status', 'enrolled');
        })->count();

        $stats = [
            'total_enrollments'    => $semester->enrollments()->count(),
            'active_enrollments'   => $semester->enrollments()->where('status', 'enrolled')->count(),
            'dropped_enrollments'  => $semester->enrollments()->where('status', 'dropped')->count(),
            'completed_enrollments'=> $semester->enrollments()->where('status', 'completed')->count(),
            'courses_offered'      => $semester->enrollments()->distinct()->count('course_id'),
            'students_enrolled'    => $studentsEnrolled, // 
        ];

        return response()->json($stats);
    }
}
