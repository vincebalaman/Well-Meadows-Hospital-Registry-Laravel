<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class PatientHistoryController extends Controller
{
    public function index()
    {
        $patients = Patient::orderBy('last_name')->get();

        return view('patient-history.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        // Uses your view_patient_medical_history Postgres view
        $history = DB::table('view_patient_medical_history')
            ->where('patient_no', $patient->patient_no)
            ->get();

        return view('patient-history.show', compact('patient', 'history'));
    }
}