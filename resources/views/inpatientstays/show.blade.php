<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('In-Patient Stay Details') }}
            </h2>
            <a href="{{ route('inpatientstays.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Stays
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Stay ID</label>
                                <p class="text-lg font-semibold">{{ $inPatientStays->stay_id }}</p>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                                <p class="text-lg">{{ $inPatientStays->patient->first_name }} {{ $inPatientStays->patient->last_name }}</p>
                                <p class="text-sm text-gray-600">ID: {{ $inPatientStays->patient->patient_no }}</p>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ward</label>
                                <p class="text-lg">{{ $inPatientStays->ward->name }}</p>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bed Assignment</label>
                                <p class="text-lg">
                                    @if ($inPatientStays->bed)
                                        {{ $inPatientStays->bed->bed_number }}
                                    @else
                                        <span class="text-gray-500 italic">Waiting for bed assignment</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full {{ $inPatientStays->status === 'discharged' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($inPatientStays->status) }}
                                </span>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date Placed (Waiting)</label>
                                <p class="text-lg">{{ $inPatientStays->date_placed_waiting }}</p>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date Placed (Ward)</label>
                                <p class="text-lg">{{ $inPatientStays->date_placed_ward ?? 'N/A' }}</p>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Expected Duration</label>
                                <p class="text-lg">{{ $inPatientStays->expected_duration }} day(s)</p>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Expected Leave Date</label>
                                <p class="text-lg">{{ $inPatientStays->expected_leave ?? 'N/A' }}</p>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Actual Leave Date</label>
                                <p class="text-lg">{{ $inPatientStays->actual_leave ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if ($inPatientStays->status !== 'discharged')
                        <div class="mt-8 pt-6 border-t">
                            <form action="{{ route('inpatientstays.destroy', $inPatientStays->stay_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to discharge this patient?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Discharge Patient') }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
