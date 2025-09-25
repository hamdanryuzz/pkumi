<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Period;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of enrollments
     */
    public function index(Request $request)
    {
        $query = Enrollment::with(['student', 'course', 'period']);

        // Filter by period
        if ($request->filled('period_id')) {
            $query->where('period_id', $request->period_id);
        }

        // Filter by course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by student name
        if ($request->filled('search')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nim', 'like', '%' . $request->search . '%');
            });
        }

        $enrollments = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get filter options
        $periods = Period::orderBy('start_date', 'desc')->get();
        $courses = Course::orderBy('name')->get();

        return view('enrollments.index', compact('enrollments', 'periods', 'courses'));
    }

    /**
     * Show the form for creating a new enrollment
     */
    public function create(Request $request)
{
    $periods = Period::where('status', 'active')
        ->orWhere('status', 'draft')
        ->orderBy('start_date', 'desc')
        ->get();
        
    $courses = Course::orderBy('name')->get();
    
    // Tambahkan data untuk filter
    $years = Year::orderBy('name', 'desc')->get();
    $studentClasses = StudentClass::orderBy('name')->get();
    
    // Query students dengan filter
    $studentsQuery = Student::where('status', 'active');
    
    // Filter berdasarkan angkatan jika ada
    if ($request->has('year_id') && $request->year_id) {
        $studentsQuery->where('year_id', $request->year_id);
    }
    
    // Filter berdasarkan kelas jika ada
    if ($request->has('student_class_id') && $request->student_class_id) {
        $studentsQuery->where('student_class_id', $request->student_class_id);
    }
    
    $students = $studentsQuery->orderBy('name')->get();

    // Pre-select values
    $selectedPeriod = $request->get('period_id');
    $selectedCourse = $request->get('course_id');
    $selectedYear = $request->get('year_id');
    $selectedClass = $request->get('student_class_id');

    return view('enrollments.create', compact(
        'periods', 
        'courses', 
        'students', 
        'years',
        'studentClasses',
        'selectedPeriod', 
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
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'period_id' => 'required|exists:periods,id',
            'enrollment_date' => 'nullable|date',
            'status' => ['nullable', Rule::in(['enrolled', 'dropped', 'completed'])]
        ]);

        // Check if student is active
        $student = Student::find($validated['student_id']);
        if ($student->status !== 'active') {
            return back()->withErrors(['student_id' => 'Mahasiswa tidak aktif.'])->withInput();
        }

        // Check if period allows enrollment
        $period = Period::find($validated['period_id']);
        if (!$this->canEnrollInPeriod($period)) {
            return back()->withErrors(['period_id' => 'Periode tidak dalam masa pendaftaran.'])->withInput();
        }

        // Check for duplicate enrollment
        $existingEnrollment = Enrollment::where([
            'student_id' => $validated['student_id'],
            'course_id' => $validated['course_id'],
            'period_id' => $validated['period_id']
        ])->first();

        if ($existingEnrollment) {
            return back()->withErrors(['duplicate' => 'Mahasiswa sudah terdaftar pada mata kuliah ini di periode yang sama.'])->withInput();
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
        $enrollment->load(['student', 'course', 'period']);
        
        // Get related grade if exists
        $grade = $enrollment->student->grade()
            ->where('course_id', $enrollment->course_id)
            ->where('period_id', $enrollment->period_id)
            ->first();

        return view('enrollments.show', compact('enrollment', 'grade'));
    }

    /**
     * Show the form for editing the specified enrollment
     */
    public function edit(Enrollment $enrollment)
    {
        $periods = Period::orderBy('start_date', 'desc')->get();
        $courses = Course::orderBy('name')->get();
        $students = Student::where('status', 'active')->orderBy('name')->get();

        return view('enrollments.edit', compact('enrollment', 'periods', 'courses', 'students'));
    }

    /**
     * Update the specified enrollment
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'period_id' => 'required|exists:periods,id',
            'enrollment_date' => 'required|date',
            'status' => ['required', Rule::in(['enrolled', 'dropped', 'completed'])]
        ]);

        // Check for duplicate enrollment (excluding current record)
        $existingEnrollment = Enrollment::where([
            'student_id' => $validated['student_id'],
            'course_id' => $validated['course_id'],
            'period_id' => $validated['period_id']
        ])->where('id', '!=', $enrollment->id)->first();

        if ($existingEnrollment) {
            return back()->withErrors(['duplicate' => 'Mahasiswa sudah terdaftar pada mata kuliah ini di periode yang sama.'])->withInput();
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
        // Check if enrollment has associated grades
        $hasGrades = $enrollment->student->grade()
            ->where('course_id', $enrollment->course_id)
            ->where('period_id', $enrollment->period_id)
            ->exists();

        if ($hasGrades) {
            return back()->withErrors(['delete' => 'Tidak dapat menghapus pendaftaran yang sudah memiliki nilai.']);
        }

        $enrollment->delete();

        return redirect()->route('enrollments.index')
            ->with('success', 'Pendaftaran berhasil dihapus!');
    }

    /**
     * Bulk enrollment for multiple students
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'period_id' => 'required|exists:periods,id',
        ]);

        $period = Period::find($validated['period_id']);
        if (!$this->canEnrollInPeriod($period)) {
            return back()->withErrors(['period_id' => 'Periode tidak dalam masa pendaftaran.']);
        }

        $successCount = 0;
        $skippedCount = 0;
        $errors = [];

        foreach ($validated['student_ids'] as $studentId) {
            // Check if student is active
            $student = Student::find($studentId);
            if ($student->status !== 'active') {
                $errors[] = "Mahasiswa {$student->name} tidak aktif.";
                continue;
            }

            // Check for duplicate enrollment
            $existingEnrollment = Enrollment::where([
                'student_id' => $studentId,
                'course_id' => $validated['course_id'],
                'period_id' => $validated['period_id']
            ])->exists();

            if ($existingEnrollment) {
                $skippedCount++;
                continue;
            }

            // Create enrollment
            Enrollment::create([
                'student_id' => $studentId,
                'course_id' => $validated['course_id'],
                'period_id' => $validated['period_id'],
                'enrollment_date' => Carbon::now()->toDateString(),
                'status' => 'enrolled'
            ]);

            $successCount++;
        }

        $message = "Berhasil mendaftarkan {$successCount} mahasiswa";
        if ($skippedCount > 0) {
            $message .= ", {$skippedCount} mahasiswa dilewati (sudah terdaftar)";
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->with('warning', $message);
        }

        return redirect()->route('enrollments.index')
            ->with('success', $message);
    }

    /**
     * Get students enrolled in a specific course and period
     */
    public function getEnrolledStudents(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'period_id' => 'required|exists:periods,id'
        ]);

        $students = Student::whereHas('enrollments', function ($query) use ($request) {
            $query->where('course_id', $request->course_id)
                  ->where('period_id', $request->period_id)
                  ->where('status', 'enrolled');
        })->with(['enrollments' => function ($query) use ($request) {
            $query->where('course_id', $request->course_id)
                  ->where('period_id', $request->period_id);
        }])->get();

        return response()->json([
            'students' => $students,
            'count' => $students->count()
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
     * Check if enrollment is allowed for a period
     */
    private function canEnrollInPeriod(Period $period): bool
    {
        if ($period->status === 'completed') {
            return false;
        }

        // Check if current date is within enrollment period
        $now = Carbon::now()->toDateString();
        return $now >= $period->enrollment_start_date && $now <= $period->enrollment_end_date;
    }

    /**
     * Export enrollments to Excel/CSV
     */
    public function export(Request $request)
    {
        $query = Enrollment::with(['student', 'course', 'period']);

        if ($request->filled('period_id')) {
            $query->where('period_id', $request->period_id);
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
            fputcsv($file, ['NIM', 'Nama Mahasiswa', 'Mata Kuliah', 'Periode', 'Tanggal Daftar', 'Status']);
            
            foreach ($enrollments as $enrollment) {
                fputcsv($file, [
                    $enrollment->student->nim,
                    $enrollment->student->name,
                    $enrollment->course->name,
                    $enrollment->period->name,
                    $enrollment->enrollment_date->format('d-m-Y'),
                    ucfirst($enrollment->status)
                ]);
            }
            
            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
