<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffPatientAssignmentRequest;
use App\Models\InPatientStay;
use App\Models\Staff;
use App\Models\StaffPatientAssignment;

class StaffPatientAssignmentController extends Controller
{
    public function index()
    {
        $assignments = StaffPatientAssignment::with(['staff', 'stay.patient'])
            ->orderBy('assignment_id', 'desc')
            ->paginate(15);

        return view('staff-assignments.index', compact('assignments'));
    }

    public function create()
    {
        return view('staff-assignments.create', [
            'staff' => Staff::orderBy('last_name')->get(),
            'stays' => InPatientStay::with('patient')
                ->whereNull('actual_leave')
                ->get(),
        ]);
    }

    public function store(StaffPatientAssignmentRequest $request)
    {
        StaffPatientAssignment::create($request->validated());

        return redirect()->route('staff-assignments.index')
            ->with('success', 'Staff assigned to patient.');
    }

    public function show(StaffPatientAssignment $staffAssignment)
    {
        $staffAssignment->load(['staff', 'stay.patient']);

        return view('staff-assignments.show', ['assignment' => $staffAssignment]);
    }

    public function edit(StaffPatientAssignment $staffAssignment)
    {
        return view('staff-assignments.edit', [
            'assignment' => $staffAssignment,
            'staff' => Staff::orderBy('last_name')->get(),
            'stays' => InPatientStay::with('patient')->get(),
        ]);
    }

    public function update(StaffPatientAssignmentRequest $request, StaffPatientAssignment $staffAssignment)
    {
        $staffAssignment->update($request->validated());

        return redirect()->route('staff-assignments.index')
            ->with('success', 'Assignment updated.');
    }

    public function destroy(StaffPatientAssignment $staffAssignment)
    {
        $staffAssignment->delete();

        return redirect()->route('staff-assignments.index')
            ->with('success', 'Assignment removed.');
    }
}