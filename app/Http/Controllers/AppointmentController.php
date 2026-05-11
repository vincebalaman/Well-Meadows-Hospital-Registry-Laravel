<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with(['patient', 'consultant'])->latest('app_date_time')->paginate(15);

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $patients = Patient::orderBy('first_name')->orderBy('last_name')->get();
        $staff = Staff::orderBy('first_name')->orderBy('last_name')->get();

        return view('appointments.create', compact('patients', 'staff'));
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
            'app_no' => 'required|string|max:15|unique:appointments,app_no',
            'patient_no' => 'required|string|exists:patients,patient_no',
            'consultant_staff_no' => 'required|string|exists:staff,staff_no',
            'app_date_time' => 'required|date',
            'exam_room' => 'nullable|string|max:10',
        ]);

        try {
            DB::statement('CALL schedule_appointment(?, ?, ?, ?, ?)', [
                $validated['app_no'],
                $validated['patient_no'],
                $validated['consultant_staff_no'],
                $validated['app_date_time'],
                $validated['exam_room'] ?? null,
            ]);

            return redirect()->route('appointments.index')
                ->with('success', 'Appointment scheduled successfully!');

        } catch (\Exception $e) {
            Log::error('Appointment scheduling failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['database_error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the patient medical history view.
     */
    public function medicalHistory()
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $history = DB::table('view_patient_medical_history')
            ->orderByDesc('visit_date')
            ->paginate(20);

        return view('appointments.medical_history', compact('history'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $patients = Patient::orderBy('first_name')->orderBy('last_name')->get();
        $staff = Staff::orderBy('first_name')->orderBy('last_name')->get();

        return view('appointments.edit', compact('appointment', 'patients', 'staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'patient_no' => 'required|string|exists:patients,patient_no',
            'consultant_staff_no' => 'required|string|exists:staff,staff_no',
            'app_date_time' => 'required|date',
            'exam_room' => 'nullable|string|max:10',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }
}
