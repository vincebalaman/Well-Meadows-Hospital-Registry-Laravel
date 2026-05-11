<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Comprehensive Patient Record') }}
            </h2>
            <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Patients
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Patient Header Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-4 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Patient ID</label>
                            <p class="text-lg font-semibold">{{ $patient->patient_no }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <p class="text-lg font-semibold">{{ $patient->first_name }} {{ $patient->last_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <p class="text-lg">{{ $patient->dob }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sex</label>
                            <p class="text-lg">{{ $patient->sex }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comprehensive Record Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Medical History & Prescriptions</h3>
                    <table class="w-full text-sm divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Treatment Plan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prescribed Drug</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosage</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Appointment</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($records as $record)
                                <tr>
                                    <td class="px-6 py-4">{{ $record->latest_diagnosis ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $record->treatment_plan ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $record->prescribed_drug ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $record->dosage ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $record->medication_start ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $record->medication_end ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $record->last_appointment ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 italic">
                                        No comprehensive record data available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
