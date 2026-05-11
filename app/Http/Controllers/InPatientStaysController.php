<?php

namespace App\Http\Controllers;

use App\Models\InPatientStays;
use App\Models\Patient;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InPatientStaysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $stays = InPatientStays::with('patient', 'ward', 'bed')
            ->orderByDesc('date_placed_waiting')
            ->paginate(20);

        return view('inpatientstays.index', compact('stays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $patients = Patient::orderBy('first_name')->get();
        $wards = Ward::orderBy('ward_name')->get();

        return view('inpatientstays.create', compact('patients', 'wards'));
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
            'patient_no' => 'required|string|exists:patients,patient_no',
            'ward_id' => 'required|integer|exists:wards,ward_id',
            'expected_duration' => 'required|integer|min:1',
        ]);

        try {
            DB::statement('CALL request_ward_admission(?, ?, ?)', [
                $validated['patient_no'],
                $validated['ward_id'],
                $validated['expected_duration'],
            ]);

            return redirect()->route('inpatientstays.index')
                ->with('success', 'Ward admission request submitted successfully!');

        } catch (\Exception $e) {
            Log::error('Ward admission request failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['database_error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(InPatientStays $inPatientStays)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        return view('inpatientstays.show', compact('inPatientStays'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InPatientStays $inPatientStays)
    {
        return redirect()->route('inpatientstays.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InPatientStays $inPatientStays)
    {
        return redirect()->route('inpatientstays.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InPatientStays $inPatientStays)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        try {
            DB::statement('CALL discharge_patient(?)', [
                $inPatientStays->stay_id,
            ]);

            $updated = DB::table('in_patient_stays')
                ->where('stay_id', $inPatientStays->stay_id)
                ->where('status', 'discharged')
                ->exists();

            if (! $updated) {
                DB::table('in_patient_stays')
                    ->where('stay_id', $inPatientStays->stay_id)
                    ->update([
                        'actual_leave' => DB::raw('CURRENT_DATE'),
                        'status' => 'discharged',
                    ]);
            }

            return redirect()->route('inpatientstays.index')
                ->with('success', 'Patient discharged successfully!');

        } catch (\Exception $e) {
            Log::error('Patient discharge failed: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['database_error' => 'Database Error: ' . $e->getMessage()]);
        }
    }
}
