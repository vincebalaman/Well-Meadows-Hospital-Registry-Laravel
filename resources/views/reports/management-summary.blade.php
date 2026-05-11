<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Management Summary</h2>
    </x-slot>

    <div class="py-12 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex gap-4">
            <a href="{{ route('reports.ward-occupancy') }}" class="px-4 py-2 bg-gray-200 rounded">Ward Occupancy</a>
            <a href="{{ route('reports.management-summary') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Management Summary</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Patient Metrics</h3>
                <p class="text-sm">Total Registered Patients: <span class="font-bold">{{ $summary->total_registered_patients ?? 0 }}</span></p>
                <p class="text-sm">Current In-Patients: <span class="font-bold">{{ $summary->current_inpatients ?? 0 }}</span></p>
                <p class="text-sm">On Waiting List: <span class="font-bold">{{ $summary->waiting_list_count ?? 0 }}</span></p>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Financial</h3>
                <p class="text-sm">Total Gross Revenue: <span class="font-bold">{{ number_format($summary->total_gross_revenue ?? 0, 2) }}</span></p>
                <p class="text-sm">Total Inventory Value: <span class="font-bold">{{ number_format($summary->total_inventory_value ?? 0, 2) }}</span></p>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Staffing</h3>
                <p class="text-sm">Total Staff: <span class="font-bold">{{ $summary->total_staff_count ?? 0 }}</span></p>
                <p class="text-sm">On Current Rota: <span class="font-bold">{{ $summary->staff_on_current_rota ?? 0 }}</span></p>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Operational Alerts</h3>
                <p class="text-sm">Items Below Reorder Level:
                    <span class="font-bold {{ ($summary->items_below_reorder_level ?? 0) > 0 ? 'text-red-600' : '' }}">
                        {{ $summary->items_below_reorder_level ?? 0 }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>