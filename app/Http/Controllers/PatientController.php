<?php

namespace App\Http\Controllers;

use App\Models\LocalDoctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = \App\Models\Patient::orderBy('date_registered', 'desc')->get();

        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch clinics to populate the dropdown in the form
        $clinics = LocalDoctor::all(); 
        return view('patients.create', compact('clinics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::statement("CALL register_patient(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                $request->patient_no,       // p_no
                $request->first_name,       // p_fname
                $request->last_name,        // p_lname
                $request->address ?? 'N/A', // p_addr
                $request->tel_no ?? 'N/A',  // p_tel
                $request->dob,              // p_dob (Ensure format is YYYY-MM-DD)
                $request->sex ?? 'M',       // p_sex
                $request->marital_status,   // p_marital
                $request->clinic_no,        // p_clinic
                $request->nok_name,         // nok_name
                $request->nok_relationship, // nok_rel
                $request->nok_tel ?? 'N/A'  // nok_tel
            ]);
            return redirect()->route('patients.index')->with('success', 'Patient registered!');
        } catch (\Exception $e) {
            // This will catch things like Foreign Key errors (e.g., if clinic_no is invalid)
            return redirect()->back()->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        }
        
        return redirect()->route('patients.index')->with('success', 'Patient registered successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // 2. The Cascade (Delete the Next of Kin first, then the Patient)
            // This works because you defined the relationship earlier
            $patient->nextOfKin()->delete(); 
            $patient->delete();

            return redirect()->route('patients.index')->with('success', 'Patient and associated Next of Kin deleted.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Delete failed: ' . $e->getMessage()]);
        }
    }
}
