<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Billing Details') }}
            </h2>
            <a href="{{ route('patientbillings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Billing
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bill ID</label>
                            <p class="text-lg font-semibold">{{ $bill->bill_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stay ID</label>
                            <p class="text-lg">{{ $bill->stay_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Patient</label>
                            <p class="text-lg">{{ $bill->stay->patient->first_name }} {{ $bill->stay->patient->last_name }}</p>
                            <p class="text-sm text-gray-600">ID: {{ $bill->stay->patient->patient_no }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $bill->payment_status === 'Cleared' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $bill->payment_status }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-6 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Original Invoice</p>
                            <p class="text-2xl font-semibold">{{ number_format($bill->total_amount, 2) }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Amount Paid</p>
                            <p class="text-2xl font-semibold">{{ number_format($bill->amount_paid, 2) }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Outstanding</p>
                            <p class="text-2xl font-semibold">{{ number_format($bill->total_amount - $bill->amount_paid, 2) }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date Placed to Ward</label>
                            <p class="text-lg">{{ $bill->stay->date_placed_ward ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Actual Leave</label>
                            <p class="text-lg">{{ $bill->stay->actual_leave ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @php
                        $outstanding = (($bill->total_amount ?? 0) - ($bill->amount_paid ?? 0));
                    @endphp

                    @if ($outstanding > 0)
                        <div class="mt-6">
                            <a href="{{ route('patientbillings.edit', ['patientbilling' => $bill->bill_id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Record Payment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
