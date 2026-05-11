<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Patient Billing') }}
            </h2>
            <a href="{{ route('patientbillings.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Generate Bill
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-md bg-green-50 border border-green-200">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-sm text-center divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bill ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stay ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Outstanding</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($bills as $bill)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $bill->bill_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $bill->first_name }} {{ $bill->last_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $bill->stay_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($bill->original_invoice, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($bill->amount_paid, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($bill->outstanding_balance, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $bill->payment_status === 'Cleared' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $bill->payment_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('patientbillings.show', ['patientbilling' => $bill->bill_id]) }}" class="text-blue-600 hover:text-blue-900">
                                            View
                                        </a>
                                        @if ($bill->outstanding_balance > 0)
                                            <a href="{{ route('patientbillings.edit', ['patientbilling' => $bill->bill_id]) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">
                                                Record Payment
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500 italic">
                                        No billing records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($bills->hasPages())
                <div class="mt-4">
                    {{ $bills->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
