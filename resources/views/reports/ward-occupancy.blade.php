<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Ward Occupancy Report</h2>
    </x-slot>

    <div class="py-12 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex gap-4">
            <a href="{{ route('reports.ward-occupancy') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Ward Occupancy</a>
            <a href="{{ route('reports.management-summary') }}" class="px-4 py-2 bg-gray-200 rounded">Management Summary</a>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">Ward</th>
                        <th class="px-4 py-2 text-right">Total Beds</th>
                        <th class="px-4 py-2 text-right">Occupied</th>
                        <th class="px-4 py-2 text-right">Occupancy %</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $row->ward_name }} (#{{ $row->ward_id }})</td>
                            <td class="px-4 py-2 text-right">{{ $row->total_beds }}</td>
                            <td class="px-4 py-2 text-right">{{ $row->occupied_beds }}</td>
                            <td class="px-4 py-2 text-right">{{ $row->occupancy_rate }}%</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">No data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>