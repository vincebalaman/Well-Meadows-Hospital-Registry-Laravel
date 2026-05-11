@csrf
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Patient Stay</label>
        <select name="stay_id" class="w-full mt-1 border-gray-300 rounded">
            <option value="">— Select —</option>
            @foreach ($stays as $stay)
                <option value="{{ $stay->stay_id }}"
                    @selected((int) old('stay_id', $bill->stay_id ?? 0) === $stay->stay_id)>
                    Stay #{{ $stay->stay_id }} — {{ $stay->patient?->full_name }}
                </option>
            @endforeach
        </select>
        @error('stay_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Total Amount</label>
        <input type="number" step="0.01" name="total_amount" id="total_amount"
               value="{{ old('total_amount', $bill->total_amount ?? '') }}"
               class="w-full mt-1 border-gray-300 rounded" required>
        @error('total_amount') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Amount Paid</label>
        <input type="number" step="0.01" name="amount_paid" id="amount_paid"
               value="{{ old('amount_paid', $bill->amount_paid ?? '0.00') }}"
               class="w-full mt-1 border-gray-300 rounded" required>
        @error('amount_paid') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Outstanding Balance</label>
        <input type="number" step="0.01" id="outstanding_balance"
               value="{{ old('outstanding_balance', $bill->outstanding ?? '0.00') }}"
               class="w-full mt-1 border-gray-300 rounded bg-gray-100" readonly>
    </div>

    <div>
        <label class="block text-sm font-medium">Status</label>
        <select name="payment_status" id="payment_status" class="w-full mt-1 border-gray-300 rounded">
            @php
                $statuses = ['Pending', 'Partial', 'Cleared'];
            @endphp
            @foreach ($statuses as $status)
                <option value="{{ $status }}"
                    @selected(old('payment_status', $bill->payment_status ?? 'Pending') === $status)>
                    {{ $status }}
                </option>
            @endforeach
        </select>
        @error('payment_status') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const totalAmount = document.getElementById('total_amount');
    const amountPaid = document.getElementById('amount_paid');
    const outstandingBalance = document.getElementById('outstanding_balance');
    const paymentStatus = document.getElementById('payment_status');

    function calculateOutstanding() {
        const total = parseFloat(totalAmount.value) || 0;
        const paid = parseFloat(amountPaid.value) || 0;
        const outstanding = total - paid;

        outstandingBalance.value = outstanding.toFixed(2);

        // Auto-update status based on amounts
        if (outstanding <= 0) {
            paymentStatus.value = 'Cleared';
        } else if (paid > 0) {
            paymentStatus.value = 'Partial';
        } else {
            paymentStatus.value = 'Pending';
        }
    }

    totalAmount.addEventListener('input', calculateOutstanding);
    amountPaid.addEventListener('input', calculateOutstanding);

    // Initial calculation
    calculateOutstanding();
});
</script>

<div class="mt-6 flex gap-2">
    <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save</button>
    <a href="{{ route('patientbillings.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
</div>