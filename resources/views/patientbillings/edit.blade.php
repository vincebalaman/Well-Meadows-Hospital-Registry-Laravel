<x-app-layout>
    <form method="POST" action="{{ route('patientbillings.update', $bill->bill_id) }}">
        @csrf
        @method('PUT')
        <label>Amount to Pay</label>
        <input type="number" name="amount_paid" step="0.01" max="{{ $bill->outstanding_balance }}">
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">
            Submit Payment
        </button>
    </form>
</x-app-layout>