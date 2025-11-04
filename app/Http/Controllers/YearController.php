<?php

namespace App\Http\Controllers;

use App\Models\Year;
use App\Models\Period;
use Illuminate\Http\Request;

class YearController extends Controller
{
    /**
     * Menampilkan daftar tahun dengan filter period dan search.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $periodFilter = $request->input('period_id');

        $periods = Period::orderBy('name')->get();

        $years = Year::query()
            ->with('period')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($periodFilter, function ($query, $periodFilter) {
                return $query->where('period_id', $periodFilter);
            })
            ->latest()
            ->orderByRaw("CAST(REGEXP_SUBSTR(name, '[0-9]+') AS UNSIGNED) DESC")
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('years.index', compact('years', 'search', 'periods', 'periodFilter'));
    }

    /**
     * Menampilkan form tambah tahun.
     */
    public function create()
    {
        $periods = Period::orderBy('name')->get();
        return view('years.create', compact('periods'));
    }

    /**
     * Simpan data tahun baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year_number' => 'required|string',
            'period_id' => 'required|exists:periods,id',
        ]);

        $name = 'Angkatan ' . $request->year_number;

        Year::create([
            'name' => $name,
            'period_id' => $request->period_id,
        ]);

        return redirect()->route('years.index')->with('success', 'Angkatan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail tahun.
     */
    public function show($id)
    {
        $year = Year::with([
            'period',
            'studentClasses' => function($query) {
                $query->withCount('students')
                    ->withCount('enrollments')
                    ->orderBy('name');
            }
        ])->findOrFail($id);

        return view('years.show', compact('year'));
    }

    /**
     * Menampilkan form edit tahun.
     */
    public function edit($id)
    {
        $year = Year::findOrFail($id);
        $periods = Period::orderBy('name')->get();
        
        // Parse existing name to extract year_number and semester
        $nameParts = explode(' ', $year->name);
        $yearNumber = isset($nameParts[1]) ? $nameParts[1] : '';
        $semester = isset($nameParts[2]) ? $nameParts[2] : 'Ganjil';

        return view('years.edit', compact('year', 'periods', 'yearNumber', 'semester'));
    }

    /**
     * Update data tahun.
     */
    public function update(Request $request, $id)
    {
        $year = Year::findOrFail($id);
        
        $request->validate([
            'year_number' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
            'period_id' => 'required|exists:periods,id',
        ]);

        $name = 'Angkatan ' . $request->year_number . ' ' . $request->semester;

        $year->update([
            'name' => $name,
            'period_id' => $request->period_id,
        ]);

        return redirect()->route('years.index')->with('success', 'Angkatan berhasil diperbarui.');
    }

    /**
     * Hapus data tahun.
     */
    public function destroy($id)
    {
        $year = Year::findOrFail($id);
        $year->delete();

        return redirect()->route('years.index')->with('success', 'Angkatan berhasil dihapus.');
    }
}
