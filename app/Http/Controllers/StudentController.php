<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('grade')->paginate(15);
        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:students',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $student = Student::create($request->all());
        Grade::create(['student_id' => $student->id]);

        return redirect()->route('students.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nim' => 'required|unique:students,nim,' . $student->id,
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $student->update($request->all());

        return redirect()->route('students.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')
            ->with('success', 'Mahasiswa berhasil dihapus.');
    }

    public function export()
    {
        return Excel::download(new StudentsExport, 'students.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new StudentsImport, $request->file('file'));

        return redirect()->route('students.index')
            ->with('success', 'Data mahasiswa berhasil diimpor.');
    }
}