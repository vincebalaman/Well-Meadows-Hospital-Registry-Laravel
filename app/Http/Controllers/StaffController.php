<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Staff and Patients are not allowed to browse the full index directory of personnel
        if (in_array($user->role, ['staff', 'patient'])) {
            abort(403, 'Unauthorized access to the staff directory.');
        }

        $staffMembers = Staff::all();
        
        return view('staffs.index', compact('staffMembers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!in_array($user->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        // Enforce constraint: If a staff user already has a bound registry record, stop them from creating duplicates
        if ($user->role === 'staff' && $user->staff()->exists()) {
            return redirect()->route('dashboard')->with('error', 'Your staff registry profile has already been completed.');
        }

        return view('staffs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!in_array($user->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized operation.');
        }

        // Double check entry redundancy at execution runtime for staff accounts
        if ($user->role === 'staff' && $user->staff()->exists()) {
            return redirect()->route('dashboard')->with('error', 'Profile submission blocked: Single entry limit reached.');
        }

        $request->validate([
            'staff_no'   => 'required|string|max:10|unique:staff,staff_no',
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'dob'        => 'required|date',
            'sex'        => 'required|in:M,F', 
            'nin'        => 'required|string|max:15|unique:staff,nin', 
        ]);

        try {
            DB::beginTransaction();

            // Call the Stored Procedure
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

            // Safely map the newly created record row back to the authenticated user account table column
            $newlyCreatedStaff = Staff::where('staff_no', $request->staff_no)->first();
            if ($newlyCreatedStaff && $user->role === 'staff') {
                $newlyCreatedStaff->user_id = $user->id;
                $newlyCreatedStaff->save();
            }

            DB::commit();

            if ($user->role === 'staff') {
                return redirect()->route('dashboard')->with('success', 'Your institutional staff operational profile has been linked and registered successfully!');
            }

            return redirect()->route('staffs.index')->with('success', 'Staff member added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Staff Registration Failed: " . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['database_error' => 'Could not save staff member. Please check your data: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Security check: Staff profiles are locked to their own entry unless the caller is an Admin
        if ($user->role === 'staff' && $user->staff?->staff_no !== $staff->staff_no) {
            abort(403, 'Unauthorized access to separate staff information files.');
        }

        if ($user->role === 'patient') {
            abort(403, 'Patients cannot view technical staff files.');
        }

        return view('staffs.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Staff can only update metrics mapped directly to their unique employee ID
        if ($user->role === 'staff' && $user->staff?->staff_no !== $staff->staff_no) {
            abort(403, 'Unauthorized context modification attempt.');
        }

        if ($user->role === 'patient') {
            abort(403, 'Unauthorized.');
        }

        return view('staffs.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->role === 'staff' && $user->staff?->staff_no !== $staff->staff_no) {
            abort(403, 'Unauthorized update payload transmission.');
        }

        // Implement validation & update code logic here...
        
        return redirect()->route($user->role === 'staff' ? 'dashboard' : 'staffs.index')
            ->with('success', 'Staff member registry tracking data updated successfully.');
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