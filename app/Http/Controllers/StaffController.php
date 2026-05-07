<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staffMembers = Staff::all();
        
        return view('staffs.index', compact('staffMembers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
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
            // 2. Call the Stored Procedure
            // Order must match: s_no, s_fname, s_lname, s_addr, s_tel, s_dob, s_sex, s_nin
            DB::statement("CALL add_staff_member(?, ?, ?, ?, ?, ?, ?, ?)", [
                $request->staff_no,
                $request->first_name,
                $request->last_name,
                $request->address ?? 'N/A',
                $request->tel_no ?? 'N/A',
                $request->dob,
                $request->sex,
                $request->nin
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        if (auth()->user()->role !== 'admin') {
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
