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
        <input type="number" step="0.01" name="total_amount"
               value="{{ old('total_amount', $bill->total_amount ?? '') }}"
               class="w-full mt-1 border-gray-300 rounded">
        @error('total_amount') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Amount Paid</label>
        <input type="number" step="0.01" name="amount_paid"
               value="{{ old('amount_paid', $bill->amount_paid ?? '0.00') }}"
               class="w-full mt-1 border-gray-300 rounded">
        @error('amount_paid') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Status</label>
        <select name="payment_status" class="w-full mt-1 border-gray-300 rounded">
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

<div class="mt-6 flex gap-2">
    <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save</button>
    <a href="{{ route('patient-billing.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
</div>