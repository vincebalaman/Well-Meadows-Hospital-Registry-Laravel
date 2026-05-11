<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\Ward;
use App\Models\InPatientStays;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $beds = Bed::with('ward')->paginate(20);
        return view('beds.index', compact('beds'));
    }

    /**
     * Show the form for populating beds in a ward.
     */
    public function populate()
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $wards = Ward::all();
        return view('beds.populate', compact('wards'));
    }

    /**
     * Store beds in a ward.
     */
    public function storePopulate(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'ward_id' => 'required|integer|exists:wards,ward_id',
            'num_to_add' => 'required|integer|min:1|max:50',
        ]);

        try {
            DB::statement('CALL populate_ward_beds(?::int, ?::int)', [
                $validated['ward_id'],
                $validated['num_to_add'],
            ]);

            return redirect()->route('beds.index')
                ->with('success', 'Beds populated successfully!');

        } catch (\Exception $e) {
            Log::error('Bed population failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['database_error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for admitting a patient to a bed.
     */
    public function admit()
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $stays = InPatientStays::with('patient', 'ward')
            ->whereNull('bed_no')
            ->where('status', 'admitted')
            ->get();

        $beds = Bed::with('ward')
            ->where('status', 'Available')
            ->get();

        return view('beds.admit', compact('stays', 'beds'));
    }

    /**
     * Admit a patient to a bed.
     */
    public function storeAdmit(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'stay_id' => 'required|integer|exists:in_patient_stays,stay_id',
            'bed_id' => 'required|integer|exists:beds,bed_id',
        ]);

        try {
            DB::statement('CALL admit_patient_to_bed(?::int, ?::int)', [
                $validated['stay_id'],
                $validated['bed_id'],
            ]);

            return redirect()->route('beds.index')
                ->with('success', 'Patient admitted to bed successfully!');

        } catch (\Exception $e) {
            Log::error('Patient admission failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['database_error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Bed $bed)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bed $bed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bed $bed)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bed $bed)
    {
        //
    }
}
