<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Patient Medical History') }}
            </h2>
            <a href="{{ route('appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to Appointments') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Patient #</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Name</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Visit Date</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosis</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Treatment Plan</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Drug</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Units/Day</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($history as $record)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $record->patient_no }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $record->patient_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $record->visit_date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $record->diagnosis ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $record->treatment_plan ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $record->drug_name ?? $record->drug_no ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $record->units_per_day ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 italic">
                                            {{ __('No medical history found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $history->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
