<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Semester;
use App\Models\Course;
use App\Models\StudentClass;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of enrollments with filters
     */
    public function index(Request $request)
    {
        $query = Enrollment::with(['studentClass', 'course', 'semester']);

        // Filter by semester
        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }

        // Filter by course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
        $kw = $request->search;

        $query->where(function ($q) use ($kw) {
            $q->whereHas('studentClass', function ($q1) use ($kw) {
                $q1->where('name', 'like', '%' . $kw . '%');
            })
            ->orWhereHas('course', function ($q2) use ($kw) {
                $q2->where('name', 'like', '%' . $kw . '%')
                ->orWhere('code', 'like', '%' . $kw . '%'); // opsional
            })
            ->orWhereHas('semester', function ($q3) use ($kw) {
                $q3->where('name', 'like', '%' . $kw . '%'); // opsional: cari di semester juga
            });
        });
    }

        $enrollments = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get filter options
        $semesters = Semester::orderBy('start_date', 'desc')->get();
        $courses = Course::orderBy('name')->get();

        return view('enrollments.index', compact('enrollments', 'semesters', 'courses'));
    }

    /**
     * Show the form for creating a new enrollment
     */
    public function create(Request $request)
    {
        $semesters = Semester::latest()->orderBy('name', 'desc')->get();
        $years = Year::orderBy('name', 'desc')->get();
        $studentClasses = StudentClass::orderBy('name')->get();
        
        // Courses akan difilter via AJAX berdasarkan student_class_id
        $courses = collect(); // Kosongkan default
        
        // Jika ada student_class_id, load courses yang terkait
        if ($request->filled('student_class_id')) {
            $courses = Course::where('student_class_id', $request->student_class_id)
                ->orderBy('name')
                ->get();
        }
        
        // Pre-select values
        $selectedSemester = $request->get('semester_id');
        $selectedCourse = $request->get('course_id');
        $selectedYear = $request->get('year_id');
        $selectedClass = $request->get('student_class_id');
        
        return view('enrollments.create', compact(
            'semesters',
            'years',
            'courses',
            'studentClasses',
            'selectedSemester',
            'selectedCourse',
            'selectedYear',
            'selectedClass'
        ));
    }

    /**
     * Store a newly created enrollment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_class_id' => 'required|exists:student_classes,id',
            'course_id' => 'required|exists:courses,id',
            'semester_id' => 'required|exists:semesters,id',
            'enrollment_date' => 'nullable|date',
            'status' => ['nullable', Rule::in(['enrolled', 'dropped', 'completed'])]
        ]);

        // Check if semester allows enrollment
        // $semester = Semester::find($validated['semester_id']);
        // if (!$this->canEnrollInSemester($semester)) {
        //     return back()->withErrors(['semester_id' => 'Semester tidak dalam masa pendaftaran.'])->withInput();
        // }

        // Check for duplicate enrollment
        $existingEnrollment = Enrollment::where([
            'student_class_id' => $validated['student_class_id'],
            'course_id' => $validated['course_id'],
            'semester_id' => $validated['semester_id']
        ])->first();

        if ($existingEnrollment) {
            return back()->withErrors(['duplicate' => 'Kelas sudah terdaftar pada mata kuliah ini di semester yang sama.'])->withInput();
        }

        // Set default values
        $validated['enrollment_date'] = $validated['enrollment_date'] ?? Carbon::now()->toDateString();
        $validated['status'] = $validated['status'] ?? 'enrolled';

        Enrollment::create($validated);

        return redirect()->route('enrollments.index')
            ->with('success', 'Pendaftaran berhasil dibuat!');
    }

    /**
     * Display the specified enrollment
     */
    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['studentClass.students', 'course', 'semester']);

        return view('enrollments.show', compact('enrollment'));
    }

    /**
     * Show the form for editing the specified enrollment
     */
    public function edit(Enrollment $enrollment)
    {
        $semesters = Semester::orderBy('start_date', 'desc')->get();
        $courses = Course::orderBy('name')->get();
        $years = Year::orderBy('name', 'desc')->get();
        $studentClasses = StudentClass::with('year')->orderBy('name')->get();

        return view('enrollments.edit', compact('enrollment', 'semesters', 'courses', 'years', 'studentClasses'));
    }

    /**
     * Update the specified enrollment
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'student_class_id' => 'required|exists:student_classes,id',
            'course_id' => 'required|exists:courses,id',
            'semester_id' => 'required|exists:semesters,id',
            'enrollment_date' => 'required|date',
            'status' => ['required', Rule::in(['enrolled', 'dropped', 'completed'])]
        ]);

        // Check for duplicate enrollment (excluding current record)
        $existingEnrollment = Enrollment::where([
            'student_class_id' => $validated['student_class_id'],
            'course_id' => $validated['course_id'],
            'semester_id' => $validated['semester_id']
        ])->where('id', '!=', $enrollment->id)->first();

        if ($existingEnrollment) {
            return back()->withErrors(['duplicate' => 'Kelas sudah terdaftar pada mata kuliah ini di semester yang sama.'])->withInput();
        }

        $enrollment->update($validated);

        return redirect()->route('enrollments.index')
            ->with('success', 'Pendaftaran berhasil diupdate!');
    }

    /**
     * Remove the specified enrollment
     */
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();

        return redirect()->route('enrollments.index')
            ->with('success', 'Pendaftaran berhasil dihapus!');
    }

    /**
     * Bulk enrollment for multiple student classes
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,id',
            'student_class_id' => 'required|exists:student_classes,id',
            'semester_id' => 'required|exists:semesters,id',
        ]);
        
        $successCount = 0;
        $skippedCount = 0;
        
        foreach ($validated['course_ids'] as $courseId) {
            // Check for duplicate enrollment
            $existingEnrollment = Enrollment::where([
                'student_class_id' => $validated['student_class_id'],
                'course_id' => $courseId,
                'semester_id' => $validated['semester_id']
            ])->exists();
            
            if ($existingEnrollment) {
                $skippedCount++;
                continue;
            }
            
            // Create enrollment
            Enrollment::create([
                'student_class_id' => $validated['student_class_id'],
                'course_id' => $courseId,
                'semester_id' => $validated['semester_id'],
                'enrollment_date' => Carbon::now()->toDateString(),
                'status' => 'enrolled'
            ]);
            
            $successCount++;
        }
        
        $message = "Berhasil mendaftarkan {$successCount} mata kuliah";
        if ($skippedCount > 0) {
            $message .= ", {$skippedCount} mata kuliah dilewati (sudah terdaftar)";
        }
        
        return redirect()->route('enrollments.index')
            ->with('success', $message);
    }

    /**
     * Get student classes enrolled in a specific course and semester
     */
    public function getEnrolledClasses(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'semester_id' => 'required|exists:semesters,id'
        ]);

        $studentClasses = StudentClass::whereHas('enrollments', function ($query) use ($request) {
            $query->where('course_id', $request->course_id)
                ->where('semester_id', $request->semester_id)
                ->where('status', 'enrolled');
        })->with(['enrollments' => function ($query) use ($request) {
            $query->where('course_id', $request->course_id)
                ->where('semester_id', $request->semester_id);
        }])->get();

        return response()->json([
            'student_classes' => $studentClasses,
            'count' => $studentClasses->count()
        ]);
    }

    /**
     * Drop enrollment (change status to dropped)
     */
    public function drop(Enrollment $enrollment)
    {
        $enrollment->update(['status' => 'dropped']);

        return back()->with('success', 'Status pendaftaran berhasil diubah menjadi dropped.');
    }

    /**
     * Reactivate enrollment (change status to enrolled)
     */
    public function reactivate(Enrollment $enrollment)
    {
        $enrollment->update(['status' => 'enrolled']);

        return back()->with('success', 'Status pendaftaran berhasil diubah menjadi enrolled.');
    }

    /**
     * Check if enrollment is allowed for a semester
     */
    private function canEnrollInSemester(Semester $semester): bool
    {
        if ($semester->status === 'completed') {
            return false;
        }

        // Check if current date is within enrollment period
        $now = Carbon::now()->toDateString();
        return $now >= $semester->enrollment_start_date && $now <= $semester->enrollment_end_date;
    }

    /**
     * Export enrollments to Excel/CSV
     */
    public function export(Request $request)
    {
        $query = Enrollment::with(['studentClass', 'course', 'semester']);

        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $enrollments = $query->get();

        // Return CSV response
        $filename = 'enrollments_' . date('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($enrollments) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, ['Kelas', 'Mata Kuliah', 'Semester', 'Tanggal Daftar', 'Status']);

            foreach ($enrollments as $enrollment) {
                $enrollmentDateFormatted = Carbon::parse($enrollment->enrollment_date)->format('d-m-Y');
                fputcsv($file, [
                    $enrollment->studentClass->name,
                    $enrollment->course->name,
                    $enrollment->semester->name,
                    $enrollmentDateFormatted,
                    ucfirst($enrollment->status)
                ]);
            }

            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function getCoursesByClass(Request $request)
    {
        $request->validate([
            'student_class_id' => 'required|exists:student_classes,id'
        ]);
        
        $courses = Course::where('student_class_id', $request->student_class_id)
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'sks']);
        
        return response()->json([
            'courses' => $courses,
            'count' => $courses->count()
        ]);
    }

    /**
     * Get student classes by year (AJAX endpoint)
     */
    public function getClassesByYear(Request $request)
    {
        $request->validate([
            'year_id' => 'required|exists:years,id'
        ]);
        
        $studentClasses = StudentClass::where('year_id', $request->year_id)
            ->orderBy('name')
            ->get(['id', 'name']);
        
        return response()->json([
            'student_classes' => $studentClasses,
            'count' => $studentClasses->count()
        ]);
    }
}
