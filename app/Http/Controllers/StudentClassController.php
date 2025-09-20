<?php

namespace App\Http\Controllers;

use App\Models\StudentClass;
use App\Models\Year;
use Illuminate\Http\Request;

class StudentClassController extends Controller
{
    /**
     * Menampilkan semua data kelas siswa.
     */
    public function index()
    {
        $studentClasses = StudentClass::with('students', 'year')->latest()->get();
        return view('student_classes.index', compact('studentClasses'));
    }

    /**
     * Menampilkan form tambah kelas siswa.
     */
    public function create()
    {
        $years = Year::all(); // untuk dropdown pilihan tahun
        return view('student_classes.create', compact('years'));
    }

    /**
     * Simpan kelas siswa baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year_id' => 'required|exists:years,id',
            'name'    => 'required|string',
        ]);

        StudentClass::create([
            'year_id' => $request->year_id,
            'name'    => $request->name,
        ]);

        return redirect()->route('student_classes.index')->with('success', 'Class created successfully.');
    }

    /**
     * Menampilkan detail kelas siswa.
     */
    public function show($id)
    {
        $studentClass = StudentClass::with('students', 'year')->findOrFail($id);
        return view('student_classes.show', compact('studentClass'));
    }

    /**
     * Menampilkan form edit kelas siswa.
     */
    public function edit($id)
    {
        $studentClass = StudentClass::findOrFail($id);
        $years = Year::all();
        return view('student_classes.edit', compact('studentClass', 'years'));
    }

    /**
     * Update data kelas siswa.
     */
    public function update(Request $request, $id)
    {
        $studentClass = StudentClass::findOrFail($id);

        $request->validate([
            'year_id' => 'required|exists:years,id',
            'name'    => 'required|string',
        ]);

        $studentClass->update([
            'year_id' => $request->year_id,
            'name'    => $request->name,
        ]);

        return redirect()->route('student_classes.index')->with('success', 'Class updated successfully.');
    }

    /**
     * Hapus data kelas siswa.
     */
    public function destroy($id)
    {
        $studentClass = StudentClass::findOrFail($id);
        $studentClass->delete();

        return redirect()->route('student_classes.index')->with('success', 'Class deleted successfully.');
    }
}
