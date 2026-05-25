<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Patient Billing</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                @endif

                {{-- Allowed both staff and admin to create new bills --}}
                @if (auth()->check() && in_array(auth()->user()->role, ['staff', 'admin']))
                    <a href="{{ route('patientbillings.create') }}"
                       class="inline-block mb-4 px-4 py-2 bg-indigo-600 text-black rounded hover:bg-indigo-700">
                        + New Bill
                    </a>
                @endif

                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Bill #</th>
                            <th class="px-4 py-2">Patient</th>
                            <th class="px-4 py-2">Stay</th>
                            <th class="px-4 py-2 text-right">Total</th>
                            {{-- Hide structural metrics if the logged-in user is a patient --}}
                            @if(auth()->check() && auth()->user()->role !== 'patient')
                                <th class="px-4 py-2 text-right">Paid</th>
                                <th class="px-4 py-2 text-right">Outstanding</th>
                            @endif
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bills as $bill)
                            <tr class="border-b">
                                <td class="px-4 py-2">#{{ $bill->bill_id }}</td>
                                <td class="px-4 py-2">{{ $bill->first_name }} {{ $bill->last_name }}</td>
                                <td class="px-4 py-2">#{{ $bill->stay_id }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($bill->total_amount, 2) }}</td>
                                
                                {{-- Hide metrics data matching the column layout definitions --}}
                                @if(auth()->check() && auth()->user()->role !== 'patient')
                                    <td class="px-4 py-2 text-right">{{ number_format($bill->amount_paid, 2) }}</td>
                                    <td class="px-4 py-2 text-right">{{ number_format($bill->outstanding, 2) }}</td>
                                @endif

                                <td class="px-4 py-2">
                                    @php
                                        $cls = match($bill->payment_status) {
                                            'Cleared' => 'bg-green-100 text-green-800',
                                            'Partial' => 'bg-yellow-100 text-yellow-800',
                                            default   => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $cls }}">
                                        {{ $bill->payment_status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-right space-x-2">
                                    {{-- All legitimate profiles can access the explicit show function --}}
                                    <a href="{{ route('patientbillings.show', $bill->bill_id) }}" class="text-indigo-600 hover:underline">View</a>
                                    
                                    {{-- Keep administrative structural manipulation isolated --}}
                                    @if (auth()->check() && in_array(auth()->user()->role, ['staff', 'admin']))
                                        <a href="{{ route('patientbillings.edit', $bill->bill_id) }}" class="text-blue-600 hover:underline">Edit</a>
                                        <form action="{{ route('patientbillings.destroy', $bill->bill_id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this bill?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:underline">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->check() && auth()->user()->role === 'patient' ? '6' : '8' }}" class="px-4 py-6 text-center text-gray-500">
                                    No bills yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $bills->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>