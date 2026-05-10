<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClinicalRecordRequest;
use App\Models\Appointment;
use App\Models\ClinicalRecord;

class ClinicalRecordController extends Controller
{
    public function index()
    {
        $records = ClinicalRecord::with('appointment.patient')
            ->orderBy('record_id', 'desc')
            ->paginate(15);

        return view('clinical-records.index', compact('records'));
    }

    public function create()
    {
        return view('clinical-records.create', [
            'appointments' => Appointment::with('patient')->orderBy('app_date_time', 'desc')->get(),
            'outcomes' => ClinicalRecord::OUTCOMES,
            'preselected' => request('app_no'),
        ]);
    }

    public function store(ClinicalRecordRequest $request)
    {
        ClinicalRecord::create($request->validated());

        return redirect()->route('clinical-records.index')
            ->with('success', 'Clinical record saved.');
    }

    public function show(ClinicalRecord $clinicalRecord)
    {
        $clinicalRecord->load('appointment.patient');

        return view('clinical-records.show', ['record' => $clinicalRecord]);
    }

    public function edit(ClinicalRecord $clinicalRecord)
    {
        return view('clinical-records.edit', [
            'record' => $clinicalRecord,
            'appointments' => Appointment::with('patient')->get(),
            'outcomes' => ClinicalRecord::OUTCOMES,
        ]);
    }

    public function update(ClinicalRecordRequest $request, ClinicalRecord $clinicalRecord)
    {
        $clinicalRecord->update($request->validated());

        return redirect()->route('clinical-records.index')
            ->with('success', 'Clinical record updated.');
    }

    public function destroy(ClinicalRecord $clinicalRecord)
    {
        $clinicalRecord->delete();

        return redirect()->route('clinical-records.index')
            ->with('success', 'Clinical record deleted.');
    }
}