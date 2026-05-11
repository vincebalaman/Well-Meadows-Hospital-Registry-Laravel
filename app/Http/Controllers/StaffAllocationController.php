<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Ward;
use App\Models\StaffAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StaffAllocationController extends Controller
{
    public function index()
    {
        $allocations = StaffAllocation::with(['staff', 'ward'])
            ->orderBy('week_beginning', 'desc')
            ->orderBy('allocation_id', 'desc')
            ->paginate(15);

        return view('staff-allocations.index', compact('allocations'));
    }

    public function create(Request $request)
    {
        $staff = Staff::orderBy('last_name')->orderBy('first_name')->get();
        $wards = Ward::orderBy('ward_name')->get();
        $selectedStaffNo = $request->query('staff_no');

        return view('staff-allocations.create', compact('staff', 'wards', 'selectedStaffNo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff_no' => 'required|string|exists:staff,staff_no',
            'ward_id' => 'required|integer|exists:wards,ward_id',
            'week_beginning' => 'required|date',
            'shift_type' => 'required|in:Early,Late,Night',
        ]);

        try {
            DB::statement('CALL assign_staff_to_ward(?, ?, ?, ?)', [
                $request->staff_no,
                $request->ward_id,
                $request->week_beginning,
                $request->shift_type,
            ]);

            return redirect()->route('staff-allocations.index')
                ->with('success', 'Staff assigned to ward successfully.');
        } catch (\Exception $e) {
            Log::error('Ward Assignment Failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['database_error' => 'Database error: ' . $e->getMessage()]);
        }
    }
}
