<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WardsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wards = Ward::with('chargeNurse')->withCount('allocations')->get();

        return view('wards.index', compact('wards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!in_array(Auth::user()?->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $chargeNurses = Staff::whereHas('contract.position', function ($query) {
            $query->where('position_name', 'Charge Nurse');
        })->orderBy('first_name')->get();

        return view('wards.create', compact('chargeNurses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!in_array(Auth::user()?->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'ward_name' => 'required|string|max:100|unique:wards,ward_name',
            'charge_nurse_id' => 'nullable|string|exists:staff,staff_no',
        ]);

        try {
            Ward::create([
                'ward_name' => $request->ward_name,
                'charge_nurse_id' => $request->charge_nurse_id,
            ]);

            return redirect()->route('wards.index')->with('success', 'Ward added successfully.');
        } catch (\Exception $e) {
            Log::error('Ward creation failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['ward_name' => 'Unable to add ward. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ward $ward)
    {
        $ward->load(['allocations.staff', 'chargeNurse']);

        return view('wards.show', compact('ward'));
    }

    public function assignStaffForm(Ward $ward)
    {
        if (!in_array(Auth::user()?->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $staff = Staff::orderBy('first_name')->get();

        return view('wards.assign', compact('ward', 'staff'));
    }

    public function storeStaffAssignment(Request $request, Ward $ward)
    {
        if (!in_array(Auth::user()?->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'staff_no' => 'required|string|exists:staff,staff_no',
            'week_beginning' => 'required|date',
            'shift_type' => 'required|in:Early,Late,Night',
        ]);

        DB::statement('CALL assign_staff_to_ward(?, ?, ?, ?)', [
            $request->staff_no,
            $ward->ward_id,
            $request->week_beginning,
            $request->shift_type,
        ]);

        return redirect()->route('wards.index')->with('success', 'Staff assigned to ward successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ward $ward)
    {
        if (!in_array(Auth::user()?->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $chargeNurses = Staff::whereHas('contract.position', function ($query) {
            $query->where('position_name', 'Charge Nurse');
        })->orderBy('first_name')->get();

        return view('wards.edit', compact('ward', 'chargeNurses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ward $ward)
    {
        if (!in_array(Auth::user()?->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'ward_name' => 'required|string|max:100|unique:wards,ward_name,' . $ward->ward_id . ',ward_id',
            'charge_nurse_id' => 'nullable|string|exists:staff,staff_no',
        ]);

        $ward->update([
            'ward_name' => $request->ward_name,
            'charge_nurse_id' => $request->charge_nurse_id,
        ]);

        return redirect()->route('wards.index')->with('success', 'Ward updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ward $ward)
    {
        if (!in_array(Auth::user()?->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        try {
            $ward->delete();
            return redirect()->route('wards.index')->with('success', 'Ward deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Ward delete failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Unable to delete ward.']);
        }
    }
}
