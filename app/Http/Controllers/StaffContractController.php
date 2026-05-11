<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\JobPosition;
use App\Models\StaffContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StaffContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        // 1. Capture the staff_no from the URL query string (?staff_no=S100)
        $staff = Staff::where('staff_no', $request->query('staff_no'))->firstOrFail();
        
        // 2. Fetch positions for the dropdown
        $positions = JobPosition::all();

        // 3. DIRECT TO THE CORRECT BLADE: 
        // This looks for resources/views/contracts/create.blade.php
        return view('contracts.create', compact('staff', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'staff_no'    => 'required|string|exists:staff,staff_no',
            'position_id' => 'required|integer|exists:job_positions,position_id',
            'type'        => 'required|in:P,T', // P for Permanent, T for Temporary
            'hours'       => 'required|numeric|min:0|max:40',
        ]);

        try {
            // Call the Stored Procedure: update_staff_contract
            // Order: p_staff_no, p_position_id, p_type, p_hours
            DB::statement("CALL update_staff_contract(?, ?, ?, ?)", [
                $request->staff_no,
                $request->position_id,
                $request->type,
                $request->hours
            ]);

            return redirect()->route('staffs.index')
                ->with('success', 'Staff contract updated successfully!');

        } catch (\Exception $e) {
            // Catch the 'Contracted hours cannot exceed 40' exception from PostgreSQL
            Log::error("Contract Update Failed: " . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['database_error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StaffContract $staffContract)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StaffContract $staffContract)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StaffContract $staffContract)
    {
        return $this->store($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StaffContract $staffContract)
    {
        //
    }
}
