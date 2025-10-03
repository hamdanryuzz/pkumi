<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    /**
     * Display a listing of the periods.
     */
    public function index()
    {
        $periods = Period::with('semesters')->latest()->get();
        return view('periods.index', compact('periods'));
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
}
