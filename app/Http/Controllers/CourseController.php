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
        
        $query = Course::with(['studentClass.year']);
        
        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('code', 'LIKE', '%' . $search . '%');
            });
        }
        
        // Year filter
        if ($yearFilter) {
            $query->whereHas('studentClass', function ($q) use ($yearFilter) {
                $q->where('year_id', $yearFilter);
            });
        }
        
        // Class filter
        if ($classFilter) {
            $query->where('student_class_id', $classFilter);
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
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:courses,code'],
            'student_class_id' => ['required', 'exists:student_classes,id']
        ]);

        Course::create($validated);
        
        return redirect()
            ->route('courses.index')
            ->with('success', 'Course berhasil dibuat.');
    }

    public function show(Course $course)
    {
        $course->load('studentClass.year');
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
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required', 'string', 'max:255',
                Rule::unique('courses', 'code')->ignore($course->id),
            ],
            'student_class_id' => ['required', 'exists:student_classes,id']
        ]);

        $course->update($validated);
        
        return redirect()
            ->route('courses.index')
            ->with('success', 'Course berhasil diperbarui.');
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
