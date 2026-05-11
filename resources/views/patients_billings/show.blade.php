<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Bill #{{ $bill->bill_id }}</h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-2">
            <p><strong>Patient:</strong> {{ $bill->stay?->patient?->full_name ?? '—' }}</p>
            <p><strong>Stay:</strong> #{{ $bill->stay_id }}</p>
            <p><strong>Total:</strong> {{ number_format($bill->total_amount, 2) }}</p>
            <p><strong>Paid:</strong> {{ number_format($bill->amount_paid, 2) }}</p>
            <p><strong>Outstanding:</strong> {{ number_format($bill->outstanding, 2) }}</p>
            <p><strong>Status:</strong>
                @php
                    $cls = match($bill->payment_status) {
                        'Cleared' => 'bg-green-100 text-green-800',
                        'Partial' => 'bg-yellow-100 text-yellow-800',
                        default   => 'bg-gray-100 text-gray-800',
                    };
                @endphp
                <span class="px-2 py-1 rounded text-xs font-semibold {{ $cls }}">{{ $bill->payment_status }}</span>
            </p>
        </div>

        @if (auth()->user()->isStaff() && $bill->payment_status !== 'Cleared')
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-3">Record a Payment</h3>
                <form action="{{ route('patient-billing.payment', $bill) }}" method="POST" class="flex gap-2 items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm font-medium">Amount</label>
                        <input type="number" step="0.01" min="0.01" name="amount"
                               class="w-full mt-1 border-gray-300 rounded" required>
                        @error('amount') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>
                    <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Record</button>
                </form>
            </div>
        @endif

        <a href="{{ route('patient-billing.index') }}" class="text-indigo-600 hover:underline">← Back to all bills</a>
    </div>
</x-app-layout>