<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ClinicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClinicalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clinicalRecords = ClinicalRecord::latest('record_id')->paginate(15);

        return view('clinicalrecords.index', compact('clinicalRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $appointments = Appointment::orderByDesc('app_date_time')->get();
        $selectedAppNo = $request->query('app_no');

        return view('clinicalrecords.create', compact('appointments', 'selectedAppNo'));
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
            'app_no' => 'required|string|exists:appointments,app_no',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'outcome' => 'required|string|in:Out-patient,Wait-list,Discharged',
        ]);

        try {
            DB::statement('CALL record_examination_outcome(?, ?, ?, ?)', [
                $validated['app_no'],
                $validated['diagnosis'],
                $validated['treatment_plan'],
                $validated['outcome'],
            ]);

            return redirect()->route('appointments.index')
                ->with('success', 'Clinical outcome recorded successfully!');

        } catch (\Exception $e) {
            Log::error('Clinical record creation failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['database_error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ClinicalRecord $clinicalRecord)
    {
        return view('clinicalrecords.show', compact('clinicalRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClinicalRecord $clinicalRecord)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $appointments = Appointment::orderByDesc('app_date_time')->get();

        return view('clinicalrecords.edit', compact('clinicalRecord', 'appointments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClinicalRecord $clinicalRecord)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'app_no' => 'required|string|exists:appointments,app_no',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'outcome' => 'required|string|in:Out-patient,Wait-list,Discharged',
        ]);

        $clinicalRecord->update($validated);

        return redirect()->route('clinicalrecords.show', $clinicalRecord)
            ->with('success', 'Clinical record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClinicalRecord $clinicalRecord)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $clinicalRecord->delete();

        return redirect()->route('clinicalrecords.index')
            ->with('success', 'Clinical record deleted successfully.');
    }
}
