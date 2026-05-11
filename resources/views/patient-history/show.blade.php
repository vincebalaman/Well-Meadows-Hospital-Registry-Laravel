<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ $patient->full_name }} — Treatment History</h2>
    </x-slot>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50"><tr>
                    <th class="px-4 py-2">Visit Date</th>
                    <th class="px-4 py-2">Diagnosis</th>
                    <th class="px-4 py-2">Treatment</th>
                    <th class="px-4 py-2">Drug</th>
                    <th class="px-4 py-2">Units / Day</th>
                </tr></thead>
                <tbody>
                    @forelse ($history as $h)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $h->visit_date }}</td>
                            <td class="px-4 py-2">{{ $h->diagnosis ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $h->treatment_plan ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $h->drug_name ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $h->units_per_day ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No history found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <a href="{{ route('patient-history.index') }}" class="inline-block mt-4 text-indigo-600 hover:underline">← Back to patients</a>
        </div>
    </div>
</x-app-layout>