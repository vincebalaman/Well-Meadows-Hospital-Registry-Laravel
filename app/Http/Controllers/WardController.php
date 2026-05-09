<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WardController extends Controller
{
    /**
     * Display a listing of the wards.
     */
    public function index()
    {
        // Eager load the chargeNurse relationship to optimize performance
        $wards = Ward::with('chargeNurse')->get();
        
        return view('wards.index', compact('wards'));
    }

    /**
     * Show the form for creating a new ward.
     */
    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        // Fetch staff members who are Charge Nurses to populate the dropdown
        // This ensures only qualified staff are assigned to manage a ward
        $chargeNurses = Staff::whereHas('contracts.position', function($query) {
            $query->where('position_name', 'Charge Nurse');
        })->get();

        return view('wards.create', compact('chargeNurses'));
    }

    /**
     * Store or Update a ward resource using the stored procedure.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ward_id'         => 'required|integer',
            'ward_name'       => 'required|string|max:100',
            'location'        => 'nullable|string|max:50',
            'tel_extn'        => 'nullable|string|max:10',
            'charge_nurse_id' => 'required|string|exists:staff,staff_no',
        ]);

        try {
            // Call the Stored Procedure: manage_ward_details
            // Order: p_ward_id, p_name, p_location, p_tel_extn, p_charge_nurse
            DB::statement("CALL manage_ward_details(?, ?, ?, ?, ?)", [
                $request->ward_id,
                $request->ward_name,
                $request->location,
                $request->tel_extn,
                $request->charge_nurse_id
            ]);

            return redirect()->route('wards.index')
                ->with('success', 'Ward details processed successfully!');

        } catch (\Exception $e) {
            Log::error("Ward Management Failed: " . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['database_error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified ward.
     */
    public function show(Ward $ward)
    {
        return view('wards.show', compact('ward'));
    }

    /**
     * Show the form for editing the specified ward.
     */
    public function edit(Ward $ward)
    {
        $chargeNurses = Staff::all(); // Or filtered by Charge Nurse position
        return view('wards.edit', compact('ward', 'chargeNurses'));
    }

    /**
     * Update is handled by store() due to the ON CONFLICT logic in your procedure.
     * However, you can map the update route here for RESTful consistency.
     */
    public function update(Request $request, Ward $ward)
    {
        return $this->store($request);
    }

    /**
     * Remove the specified ward from storage.
     */
    public function destroy(Ward $ward)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only administrators can remove ward records.');
        }

        try {
            $ward->delete();
            return redirect()->route('wards.index')->with('success', 'Ward removed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Delete failed: ' . $e->getMessage()]);
        }
    }
}