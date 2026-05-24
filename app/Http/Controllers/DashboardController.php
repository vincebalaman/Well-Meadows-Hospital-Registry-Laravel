<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. PATIENT DASHBOARD
        if ($user->role === 'patient') {
            // 1. Get the patient's record to retrieve their unique patient_no
            $patient = $user->patient; // Assuming your User model has a hasOne relationship named 'patient'

            if (!$patient) {
                return view('dashboard.patient', [
                    'medicalRecord' => null,
                    'patient' => null
                ]);
            }

            // 2. Query your comprehensive database view using the correct identifier column
            $medicalRecord = DB::table('view_comprehensive_patient_record')
                ->where('patient_no', $patient->patient_no)
                ->first(); // Use ->first() since it represents one patient's context

            // 3. Send it to the view under the variable name 'medicalRecord'
            return view('dashboard.patient', compact('patient', 'medicalRecord'));
        }

        // 2. STAFF DASHBOARD
        if ($user->isStaff()) {
            // Load staff details along with assigned ward metrics
            $staffInfo = $user->staff()->with('user')->first();

            return view('dashboard.staff', compact('staffInfo'));
        }

        // 3. ADMIN DASHBOARD
        if ($user->isAdmin()) {
            // Provide a comprehensive administration viewpoint dashboard summary
            $stats = [
                'total_patients' => Patient::count(),
                'total_staff' => Staff::count(),
                // Example of bringing in aggregate metrics from database layers
                'occupied_beds' => DB::table('beds')->where('status', 'Occupied')->count() ?? 0
            ];

            return view('dashboard.admin', compact('stats'));
        }

        abort(403, 'Unauthorized role configuration.');
    }
}