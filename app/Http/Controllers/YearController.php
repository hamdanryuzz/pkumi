<?php

namespace App\Http\Controllers;

use App\Models\Year;
use Illuminate\Http\Request;

class YearController extends Controller
{
    /**
     * Menampilkan semua data tahun.
     */
    public function index()
    {
        $years = Year::latest()->get();
        return view('years.index', compact('years'));
    }

    /**
     * Menampilkan form tambah tahun.
     */
    public function create()
    {
        return view('years.create');
    }

    /**
     * Simpan data tahun baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:years,name',
        ]);

        Year::create([
            'name' => $request->name,
        ]);

        return redirect()->route('years.index')->with('success', 'Year created successfully.');
    }

    /**
     * Menampilkan detail tahun (opsional, jarang dipakai di web CRUD).
     */
    public function show($id)
    {
        $year = Year::findOrFail($id);
        return view('years.show', compact('year'));
    }

    /**
     * Menampilkan form edit tahun.
     */
    public function edit($id)
    {
        $year = Year::findOrFail($id);
        return view('years.edit', compact('year'));
    }

    /**
     * Update data tahun.
     */
    public function update(Request $request, $id)
    {
        $year = Year::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:years,name,' . $year->id,
        ]);

        $year->update([
            'name' => $request->name,
        ]);

        return redirect()->route('years.index')->with('success', 'Year updated successfully.');
    }

    /**
     * Hapus data tahun.
     */
    public function destroy($id)
    {
        $year = Year::findOrFail($id);
        $year->delete();

        return redirect()->route('years.index')->with('success', 'Year deleted successfully.');
    }
}
