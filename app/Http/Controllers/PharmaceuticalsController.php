<?php

namespace App\Http\Controllers;

use App\Models\InPatientStays;
use App\Models\Pharmaceuticals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PharmaceuticalsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('patients.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $stays = InPatientStays::where('actual_leave', null)->orderByDesc('date_placed_waiting')->get();
        $pharmaceuticals = Pharmaceuticals::all();

        return view('pharmaceuticals.create', compact('stays', 'pharmaceuticals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'stay_id' => 'required|integer|exists:in_patient_stays,stay_id',
            'drug_no' => 'required|string|exists:pharmaceuticals,drug_no',
            'units_per_day' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'finish_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            DB::statement('CALL add_patient_medication(?::int, ?::varchar, ?::int, ?::date, ?::date)', [
                $validated['stay_id'],
                $validated['drug_no'],
                $validated['units_per_day'],
                $validated['start_date'],
                $validated['finish_date'],
            ]);

            return redirect()->route('patients.index')
                ->with('success', 'Patient medication added successfully!');

        } catch (\Exception $e) {
            Log::error('Medication addition failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['database_error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pharmaceuticals $pharmaceuticals)
    {
        return redirect()->route('patients.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pharmaceuticals $pharmaceuticals)
    {
        return redirect()->route('patients.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pharmaceuticals $pharmaceuticals)
    {
        return redirect()->route('patients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pharmaceuticals $pharmaceuticals)
    {
        if (!in_array(auth()->user()->role, ['admin'])) {
            abort(403, 'Only administrators can delete pharmaceuticals.');
        }

        $pharmaceuticals->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Pharmaceutical deleted successfully.');
    }
}
