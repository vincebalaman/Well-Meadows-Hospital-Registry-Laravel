<?php

namespace App\Http\Controllers;

use App\Models\LocalDoctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    /**
     * Display comprehensive patient record.
     */
    public function comprehensiveRecord(Patient $patient)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Security check: Patients can only view their own comprehensive record
        if ($user->role === 'patient' && $user->patient?->patient_no !== $patient->patient_no) {
            abort(403, 'Unauthorized. You can only view your own medical record.');
        }

        if (!in_array($user->role, ['admin', 'staff', 'patient'])) {
            abort(403, 'Unauthorized.');
        }

        $records = DB::table('view_comprehensive_patient_record')
            ->where('patient_no', $patient->patient_no)
            ->get();

        return view('patients.comprehensive_record', compact('patient', 'records'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Patients are not allowed to browse the full index of all registered medical patients
        if ($user->role === 'patient') {
            abort(403, 'Unauthorized access to the registry directory.');
        }

        $patients = \App\Models\Patient::orderBy('date_registered', 'desc')->get();

        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Enforce constraint: If a patient has already created their profile record card, block them from generating duplicates
        if ($user->role === 'patient' && $user->patient()->exists()) {
            return redirect()->route('dashboard')->with('error', 'You have already created your patient profile entry.');
        }

        // Fetch clinics to populate the dropdown in the form
        $clinics = LocalDoctor::all(); 
        return view('patients.create', compact('clinics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Enforce constraint: Double check entry redundancy at execution runtime
        if ($user->role === 'patient' && $user->patient()->exists()) {
            return redirect()->route('dashboard')->with('error', 'Profile submission blocked: Single entry limit reached.');
        }

        try {
            DB::beginTransaction();

            // Run your existing PostgreSQL registration stored procedure logic
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

            // Link the newly created profile back to the authenticated user's user_id column to secure the 1:1 assignment
            $newlyCreatedPatient = Patient::where('patient_no', $request->patient_no)->first();
            if ($newlyCreatedPatient && $user->role === 'patient') {
                $newlyCreatedPatient->user_id = $user->id;
                $newlyCreatedPatient->save();
            }

            DB::commit();

            if ($user->role === 'patient') {
                return redirect()->route('dashboard')->with('success', 'Your patient profile has been securely bound and registered!');
            }

            return redirect()->route('patients.index')->with('success', 'Patient registered successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            // This will catch things like Foreign Key errors (e.g., if clinic_no is invalid)
            return redirect()->back()->withInput()->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Security check: Patients are locked down to viewing their individual data card profile mapping
        if ($user->role === 'patient' && $user->patient?->patient_no !== $patient->patient_no) {
            abort(403, 'Unauthorized access to separate patient files.');
        }

        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Patients can only configure modifications on their singular allocated profile
        if ($user->role === 'patient' && $user->patient?->patient_no !== $patient->patient_no) {
            abort(403, 'Unauthorized profile target modifier context.');
        }

        $clinics = LocalDoctor::all();
        return view('patients.edit', compact('patient', 'clinics'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Prevent cross-user data updating injections
        if ($user->role === 'patient' && $user->patient?->patient_no !== $patient->patient_no) {
            abort(403, 'Unauthorized update payload transmission.');
        }

        // Add updating structural payload changes here...
        return redirect()->route($user->role === 'patient' ? 'dashboard' : 'patients.index')
            ->with('success', 'Patient details updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        // Only admin and staff roles can clear patient entries from the relational schema completely
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Cascade delete dependency structure safely
            $patient->nextOfKin()->delete(); 
            $patient->delete();

            return redirect()->route('patients.index')->with('success', 'Patient and associated Next of Kin deleted.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Delete failed: ' . $e->getMessage()]);
        }
    }
}