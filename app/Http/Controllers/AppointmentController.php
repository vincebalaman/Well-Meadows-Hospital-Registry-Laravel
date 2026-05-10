<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Staff;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient', 'consultant'])
            ->orderBy('app_date_time', 'desc')
            ->paginate(15);

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        return view('appointments.create', [
            'patients' => Patient::orderBy('last_name')->get(),
            'staff' => Staff::orderBy('last_name')->get(),
        ]);
    }

    public function store(AppointmentRequest $request)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] ?? 'pending';
        Appointment::create($data);
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment scheduled successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'consultant', 'clinicalRecord']);

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        return view('appointments.edit', [
            'appointment' => $appointment,
            'patients' => Patient::orderBy('last_name')->get(),
            'staff' => Staff::orderBy('last_name')->get(),
        ]);
    }

    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->validated());

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled.');
    }
}