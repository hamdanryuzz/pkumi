<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['studentClass.year']);
        
        // Filter berdasarkan student_class_id jika ada
        if ($request->filled('student_class_id')) {
            $query->where('student_class_id', $request->student_class_id);
        }
        
        // Filter berdasarkan search (nama atau kode course)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }
        
        $courses = $query->orderBy('name')->paginate(10)->withQueryString();
        $studentClasses = StudentClass::with('year')->orderBy('name')->get();
        
        return view('courses.index', compact('courses', 'studentClasses'));
    }

    public function create()
    {
        $studentClasses = StudentClass::with('year')->get();
        return view('courses.create', compact('studentClasses'));
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
        $studentClasses = StudentClass::with('year')->get();
        return view('courses.edit', compact('course', 'studentClasses'));
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
}
