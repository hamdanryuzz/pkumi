<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Year;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    /**
     * Display a listing of the periods.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $periods = Period::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('periods.index', compact('periods', 'search'));
    }

    /**
     * Show the form for creating a new period.
     */
    public function create()
    {
        return view('periods.create');
    }

    /**
     * Store a newly created period in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:periods,code',
            'status' => 'required|in:draft,active,completed',
        ]);

        Period::create($validated);

        return redirect()->route('periods.index')
            ->with('success', 'Period created successfully.');
    }

    /**
     * Display the specified period.
     */
    public function show(Period $period)
    {
        $period->load('semesters');
        return view('periods.show', compact('period'));
    }

    /**
     * Show the form for editing the specified period.
     */
    public function edit(Period $period)
    {
        return view('periods.edit', compact('period'));
    }

    /**
     * Update the specified period in storage.
     */
    public function update(Request $request, Period $period)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:periods,code,' . $period->id,
            'status' => 'required|in:draft,active,completed',
        ]);

        $period->update($validated);

        return redirect()->route('periods.index')
            ->with('success', 'Period updated successfully.');
    }

    /**
     * Remove the specified period from storage.
     */
    public function destroy(Period $period)
    {
        $period->delete();

        return redirect()->route('periods.index')
            ->with('success', 'Period deleted successfully.');
    }

    /**
     * Store a newly created period in storage with auto-generation.
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'name'   => ['required','string','max:255'],
            'code'   => ['required','string','max:255','unique:periods,code'],
            'status' => ['required','in:draft,active,completed'],
        ]);

        return DB::transaction(function () use ($validated) {
            // 1) Buat period
            $period = Period::create($validated);

            // 2) Ambil angka terbesar HANYA dari years.name
            //    Format yang kita cari: "Angkatan {N}" (strict)
            $max = 0;
            $names = Year::query()
                ->select('name')
                ->lockForUpdate()
                ->pluck('name');

            foreach ($names as $name) {
                if (preg_match('/^Angkatan\s+(\d+)$/i', trim($name), $m)) {
                    $max = max($max, (int) $m[1]);
                }
            }

            $next1 = $max + 1;
            $next2 = $max + 2;

            // 3) Buat 2 Year BARU – TIDAK pakai $period->clean_name
            $period->years()->createMany([
                ['name' => "Angkatan {$next1}"],
                ['name' => "Angkatan {$next2}"],
            ]);

            // 4) Buat 2 Semester – ini boleh pakai clean_name untuk label tahun ajaran
            $clean = $period->clean_name ?? trim((string) preg_replace('/[^0-9\/-]+/', '', (string) $period->name), " /-");
            $now   = Carbon::now();
            $six   = $now->copy()->addMonthsNoOverflow(6);
            $twelve= $now->copy()->addMonthsNoOverflow(12);

            $period->semesters()->createMany([
                [
                    'name'                  => "Semester Ganjil {$clean}",
                    'code'                  => $period->code . '-1',
                    'status'                => 'draft',
                    'start_date'            => $now,
                    'end_date'              => $six,
                    'enrollment_start_date' => $now,
                    'enrollment_end_date'   => $now->copy()->addMonthsNoOverflow(1),
                ],
                [
                    'name'                  => "Semester Genap {$clean}",
                    'code'                  => $period->code . '-2',
                    'status'                => 'draft',
                    'start_date'            => $six,
                    'end_date'              => $twelve,
                    'enrollment_start_date' => $six,
                    'enrollment_end_date'   => $six->copy()->addMonthNoOverflow(),
                ],
            ]);

            // 5) Buat kelas default untuk masing-masing angkatan baru
            $classNames   = ['S2 PKU A', 'S2 PKU B', 'S2 PKUP', 'S3 PKU'];
            $classPayload = array_map(fn ($n) => ['name' => $n], $classNames);

            $period->load('years');
            foreach ($period->years as $year) {
                $year->studentClasses()->createMany($classPayload);
            }

            return redirect()
                ->route('periods.index')
                ->with('success', "Period & data turunan berhasil dibuat. Angkatan {$next1} dan {$next2} ditambahkan.");
        }, 3);
    }

}
