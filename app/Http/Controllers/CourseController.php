<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudentClass;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Imports\CoursesImport;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $yearFilter = $request->input('year_id');
        $classFilter = $request->input('student_class_id');
        
        $query = Course::with(['studentClasses.year']);
        
        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('code', 'LIKE', '%' . $search . '%');
            });
        }
        
        // Year filter
        if ($yearFilter) {
            $query->whereHas('studentClasses', function ($q) use ($yearFilter) {
                $q->where('year_id', $yearFilter);
            });
        }
        
        // Class filter
        if ($classFilter) {
            $query->whereHas('studentClasses', function ($q) use ($classFilter) {
                $q->where('student_classes.id', $classFilter);
            });
        }
        
        $courses = $query->latest()->paginate(10);
        $years = Year::orderBy('name', 'desc')->get();
        $studentClasses = StudentClass::with('year')->orderBy('name')->get();
        
        return view('courses.index', compact('courses', 'years', 'studentClasses'));
    }

    public function create()
    {
        $years = Year::orderBy('name', 'desc')->get();
        $studentClasses = StudentClass::with('year')->get();
        return view('courses.create', compact('years', 'studentClasses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:courses',
            'class_pattern' => 'nullable|in:S2 PKU,S2 PKUP,S3 PKU',
            'student_class_ids' => 'nullable|array', // Terima multiple IDs
            'student_class_ids.*' => 'exists:student_classes,id',
            'sks' => 'nullable|integer',
        ]);

        $course = Course::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'class_pattern' => $validated['class_pattern'] ?? null,
            'sks' => $validated['sks'] ?? null,
        ]);

        // Hubungkan dengan kelas yang dipilih
        if (!empty($validated['student_class_ids'])) {
            $course->studentClasses()->sync($validated['student_class_ids']);
        }
        
        // Atau gunakan auto-assign berdasarkan pattern
        elseif (!empty($validated['class_pattern'])) {
            $course->assignToClassesByPattern();
        }

        return redirect()->route('courses.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan');
    }

    public function show(Course $course)
    {
        $course->load('studentClasses');
        return view('courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $course = Course::findOrFail($course->id);
        $years = Year::orderBy('name', 'desc')->get();
        $studentClasses = StudentClass::with('year')->get();
        return view('courses.edit', compact('course', 'years', 'studentClasses'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => "required|string|unique:courses,code,{$course->id}|max:50",
            'sks' => 'required|integer|min:1|max:6',
            'class_pattern' => 'nullable|in:S2 PKU,S2 PKUP,S3 PKU',
        ]);

        // Update course data
        $course->update($validated);

        // PENTING: Re-assign dengan pattern baru
        // Ini akan replace koneksi lama dengan yang baru
        $course->assignToClassesByPattern();

        return redirect()->route('courses.index', $course)
            ->with('success', "Course updated and synced to {$course->studentClasses()->count()} classes");
    }

    public function destroy(Course $course)
    {
        if ($course->grades()->exists()) {
            return redirect()
                ->route('courses.index')
                ->with('error', 'Course tidak dapat dihapus karena masih memiliki data nilai (grades).');
        }

        $course->delete();
        
        return redirect()
            ->route('courses.index')
            ->with('success', 'Course berhasil dihapus.');
    }

    
    public function import(Request $request)
    {
        $import = new CoursesImport;
        $import->import($request->file('file'));

        if ($import->getErrors()) {
            return back()->with('import_errors', $import->getErrors());
        }

        return back()->with('success', 'Data mata kuliah berhasil diimport!');
    }
    

}
