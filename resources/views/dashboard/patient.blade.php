<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Patient Workspace') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- STATE A: Patient has NOT created a profile yet --}}
                @if(!$patient)
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">Medical Profile Incomplete</h3>
                        <p class="mt-1 text-sm text-gray-500">To view your medical history, prescriptions, and billing info, please finalize your registration.</p>
                        <div class="mt-6">
                            <a href="{{ route('patients.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <span class="mr-2">+</span> Complete Profile
                            </a>
                        </div>
                    </div>

                {{-- STATE B: Profile exists! Render the Comprehensive Medical Record --}}
                @else
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Comprehensive Patient Record</h3>
                            <p class="mt-1 text-sm text-gray-500">Welcome back, {{ $patient->first_name ?? $user->name ?? 'User' }}. Your verified clinical registry details are displayed below.</p>
                        </div>

                        {{-- Patient Demographics Panel --}}
                        <div class="bg-gray-50 p-4 rounded-lg grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div><strong>Patient No:</strong> {{ $patient->patient_no ?? 'N/A' }}</div>
                            <div><strong>Contact:</strong> {{ $patient->tel_no ?? 'N/A' }}</div>
                            <div><strong>Address:</strong> {{ $patient->address ?? 'N/A' }}</div>
                        </div>

                        {{-- Clinical Records Panel --}}
                        <div class="mt-6">
                            <h4 class="font-semibold text-md text-gray-700 mb-2">Clinical Timeline & History</h4>
                            
                            {{-- Check if medicalRecord is set and not empty (handles single objects, arrays, or collections) --}}
                            @if(isset($medicalRecord) && (is_iterable($medicalRecord) ? count($medicalRecord) > 0 : $medicalRecord))
                                <div class="bg-white border rounded-lg overflow-hidden shadow-sm">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date / Appointment</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosis</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prescribed Treatment / Supplies</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                                            {{-- Wrap in iterable logic to support both multiple log results or single row objects safely --}}
                                            @foreach(is_iterable($medicalRecord) ? $medicalRecord : [$medicalRecord] as $record)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                                        {{ $record->appointment_date ?? 'No recent encounters' }}
                                                    </td>
                                                    <td class="px-6 py-4 text-gray-900 font-medium">
                                                        {{ $record->diagnosis ?? 'No diagnosis on record' }}
                                                    </td>
                                                    <td class="px-6 py-4 text-gray-600">
                                                        <div>
                                                            <strong>Drug:</strong> {{ $record->drug_name ?? 'None' }} 
                                                            @if(isset($record->dosage)) ({{ $record->dosage }}) @endif
                                                        </div>
                                                        <div class="text-xs text-gray-400 mt-1">
                                                            Deployed Item: {{ $record->item_description ?? 'None' }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 bg-yellow-50 p-3 rounded border border-yellow-100">
                                    Profile verified, but no active clinical event records or historical data logs have been posted by the ward staff yet.
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>