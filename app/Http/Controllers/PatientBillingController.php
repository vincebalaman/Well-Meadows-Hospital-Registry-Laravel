<?php

namespace App\Http\Controllers;

use App\Models\InPatientStays;
use App\Models\PatientBilling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PatientBillingController extends Controller
{
    /**
     * Display a listing of the billing records.
     */
    public function index()
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $bills = DB::table('patient_billing as b')
            ->join('in_patient_stays as s', 'b.stay_id', '=', 's.stay_id')
            ->join('patients as p', 's.patient_no', '=', 'p.patient_no')
            ->select(
                'b.bill_id',
                'b.stay_id',
                'p.patient_no',
                'p.first_name',
                'p.last_name',
                'b.total_amount',
                'b.amount_paid',
                DB::raw('(b.total_amount - b.amount_paid) as outstanding'),
                'b.payment_status'
            )
            ->orderByDesc('b.bill_id')
            ->paginate(20);

        return view('patientbillings.index', compact('bills'));
    }

    /**
     * Show the form for generating a new bill.
     */
    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $stays = InPatientStays::with('patient', 'ward')
            ->whereNull('actual_leave')
            ->where('status', '!=', 'discharged')
            ->orderByDesc('date_placed_waiting')
            ->get();

        return view('patientbillings.create', compact('stays'));
    }

    /**
     * Store a newly generated bill.
     */
    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'stay_id' => 'required|integer|exists:in_patient_stays,stay_id',
        ]);

        try {
            DB::statement('CALL generate_bill(?::int)', [
                $validated['stay_id'],
            ]);

            return redirect()->route('patientbillings.index')
                ->with('success', 'Patient bill generated successfully.');

        } catch (\Exception $e) {
            Log::error('Billing generation failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['database_error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Display a specific bill.
     */
    public function show($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $bill = PatientBilling::with('stay.patient')->findOrFail($id);

        return view('patientbillings.show', compact('bill'));
    }

    /**
     * Show the form for recording a payment.
     */
    public function edit($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $bill = PatientBilling::with('stay.patient')->findOrFail($id);
        $stays = InPatientStays::with('patient', 'ward')
            ->whereNull('actual_leave')
            ->where('status', '!=', 'discharged')
            ->orderByDesc('date_placed_waiting')
            ->get();
        $statuses = ['Pending', 'Partial', 'Cleared'];

        return view('patientbillings.edit', compact('bill', 'stays', 'statuses'));
    }

    /**
     * Record a payment against a bill.
     */
    public function payment(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            // Call the procedure from your SQL (Module 5)
            DB::statement('CALL record_payment(?::int, ?::numeric)', [
                $id,
                $validated['amount']
            ]);

            return redirect()->route('patientbillings.show', $id)
                ->with('success', 'Payment recorded and status updated!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Update the bill details.
     */
    public function update(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'stay_id' => 'required|integer|exists:in_patient_stays,stay_id',
            'total_amount' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'payment_status' => 'required|in:Pending,Partial,Cleared',
        ]);

        // Validate that amount_paid doesn't exceed total_amount
        if ($validated['amount_paid'] > $validated['total_amount']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['amount_paid' => 'Amount paid cannot exceed total amount.']);
        }

        try {
            $bill = PatientBilling::findOrFail($id);
            $bill->update([
                'stay_id' => $validated['stay_id'],
                'total_amount' => $validated['total_amount'],
                'amount_paid' => $validated['amount_paid'],
                'payment_status' => $validated['payment_status'],
            ]);

            return redirect()->route('patientbillings.index')
                ->with('success', 'Bill updated successfully!');
        } catch (\Exception $e) {
            Log::error('Bill update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['database_error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Redirect billing destroy requests back to the index.
     */
    public function destroy(PatientBilling $patientBilling)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized.');
        }

        return redirect()->route('patientbillings.index');
    }
}
