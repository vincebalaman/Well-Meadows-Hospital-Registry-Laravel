<?php

namespace App\Http\Controllers;

use App\Models\JobPosition;
use App\Models\Staff;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staffMembers = Staff::with('contract.position')->get();
        $totalStaff = Staff::count();
        $activeContracts = DB::table('staff_contracts')->count();
        $totalHours = DB::table('staff_contracts')->sum('hours_per_week');
        $avgHours = $totalStaff > 0 ? round($totalHours / $totalStaff, 1) : 0;
        $currentWeekStart = Carbon::now()->startOfWeek();
        $scheduledShifts = DB::table('staff_allocations')
            ->where('week_beginning', '>=', $currentWeekStart)
            ->count();

        return view('staffs.index', compact(
            'staffMembers',
            'totalStaff',
            'activeContracts',
            'totalHours',
            'avgHours',
            'scheduledShifts'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!in_array(Auth::user()?->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        // If you have a JobPosition model, fetch it here to populate a dropdown
        // $positions = JobPosition::all(); 

        return view('staffs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'staff_no'   => 'required|string|max:10|unique:staff,staff_no',
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'dob'        => 'required|date',
            'sex'        => 'required|in:M,F', // Matches your CHECK constraint
            'nin'        => 'required|string|max:15|unique:staff,nin', // Matches UNIQUE constraint
        ]);

        try {
            Staff::create([
                'staff_no' => $request->staff_no,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'address' => $request->address ?? 'N/A',
                'tel_no' => $request->tel_no ?? 'N/A',
                'dob' => $request->dob,
                'sex' => $request->sex,
                'nin' => $request->nin,
            ]);

            return redirect()->route('staffs.index')->with('success', 'Staff member added successfully!');

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error("Staff Registration Failed: " . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['database_error' => 'Could not save staff member. Please check your data.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        //
    }

    public function edit(Staff $staff)
    {
        if (Auth::user()?->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        return view('staffs.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        if (Auth::user()?->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'staff_no' => 'required|string|max:10|unique:staff,staff_no,' . $staff->staff_no . ',staff_no',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'address' => 'nullable|string|max:500',
            'tel_no' => 'nullable|string|max:20',
            'dob' => 'required|date',
            'sex' => 'required|in:M,F',
            'nin' => 'required|string|max:15|unique:staff,nin,' . $staff->staff_no . ',staff_no',
        ]);

        $staff->update($request->only(['staff_no', 'first_name', 'last_name', 'address', 'tel_no', 'dob', 'sex', 'nin']));

        return redirect()->route('staffs.index')->with('success', 'Staff member updated successfully!');
    }
    public function createWardAssignment(Staff $staff)
    {
        if (Auth::user()?->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $wards = Ward::all();

        return view('staffs.assign', compact('staff', 'wards'));
    }

    public function storeWardAssignment(Request $request, Staff $staff)
    {
        if (Auth::user()?->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'ward_id' => 'required|integer|exists:wards,ward_id',
            'week_beginning' => 'required|date',
            'shift_type' => 'required|in:Early,Late,Night',
        ]);

        DB::statement('CALL assign_staff_to_ward(?, ?, ?, ?)', [
            $staff->staff_no,
            $request->ward_id,
            $request->week_beginning,
            $request->shift_type,
        ]);

        return redirect()->route('staffs.index')->with('success', 'Ward assignment saved successfully.');
    }

    public function editContract(Staff $staff)
    {
        if (Auth::user()?->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $staff->load('contract.position');
        $positions = JobPosition::all();
        $contract = $staff->contract;

        return view('staffs.contract', compact('staff', 'positions', 'contract'));
    }

    public function updateContract(Request $request, Staff $staff)
    {
        if (Auth::user()?->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'position_id' => 'required|integer|exists:job_positions,position_id',
            'contract_type' => 'required|string|size:1',
            'hours_per_week' => 'required|numeric|min:0|max:40',
        ]);

        DB::statement('CALL update_staff_contract(?, ?, ?, ?)', [
            $staff->staff_no,
            $request->position_id,
            $request->contract_type,
            $request->hours_per_week,
        ]);

        return redirect()->route('staffs.index')->with('success', 'Staff contract updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        if (Auth::user()?->role !== 'admin') {
            abort(403, 'Only administrators can remove staff records.');
        }

        try {
            $staff->delete();
            return redirect()->route('staffs.index')->with('success', 'Staff member removed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Delete failed: ' . $e->getMessage()]);
        }
    }
}
