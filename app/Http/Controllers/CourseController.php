<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Tampilkan daftar course.
     */
    public function index()
    {
        // jika mau pagination: Course::latest()->paginate(10)
        $courses = Course::orderBy('name')->get();

        return view('courses.index', compact('courses'));
    }

    /**
     * Tampilkan form create.
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Simpan data baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // unik di tabel courses sesuai migration (unique di kolom code)
            'code' => ['required', 'string', 'max:255', 'unique:courses,code'],
        ]);

        Course::create($validated);

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course berhasil dibuat.');
    }

    /**
     * Detail satu course (opsional, jika diperlukan).
     */
    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }

    /**
     * Tampilkan form edit.
     */
    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    /**
     * Update data.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // unique tapi ignore id yang sedang diedit
            'code' => [
                'required', 'string', 'max:255',
                Rule::unique('courses', 'code')->ignore($course->id),
            ],
        ]);

        $course->update($validated);

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course berhasil diperbarui.');
    }

    /**
     * Hapus data.
     */
    public function destroy(Course $course)
    {
        // Cegah penghapusan jika masih punya relasi grade
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
