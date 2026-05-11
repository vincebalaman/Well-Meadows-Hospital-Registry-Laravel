<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Schedule New Appointment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('appointments.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="app_no" :value="__('Appointment Number')" />
                                <x-text-input id="app_no" class="block mt-1 w-full" type="text" name="app_no" value="{{ old('app_no') }}" required placeholder="A10001" />
                            </div>

                            <div>
                                <x-input-label for="patient_no" :value="__('Patient')" />
                                <select id="patient_no" name="patient_no" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                    <option value="">{{ __('Select a patient') }}</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->patient_no }}" {{ old('patient_no') == $patient->patient_no ? 'selected' : '' }}>
                                            {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_no }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="consultant_staff_no" :value="__('Consultant Staff')" />
                                <select id="consultant_staff_no" name="consultant_staff_no" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                    <option value="">{{ __('Select a consultant') }}</option>
                                    @foreach($staff as $member)
                                        <option value="{{ $member->staff_no }}" {{ old('consultant_staff_no') == $member->staff_no ? 'selected' : '' }}>
                                            {{ $member->first_name }} {{ $member->last_name }} ({{ $member->staff_no }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <x-input-label for="app_date_time" :value="__('Appointment Date / Time')" />
                                <x-text-input id="app_date_time" class="block mt-1 w-full" type="datetime-local" name="app_date_time" value="{{ old('app_date_time') }}" required />
                            </div>

                            <div>
                                <x-input-label for="exam_room" :value="__('Exam Room')" />
                                <x-text-input id="exam_room" class="block mt-1 w-full" type="text" name="exam_room" value="{{ old('exam_room') }}" placeholder="Room 12" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t pt-4">
                        <x-primary-button class="ml-4">
                            {{ __('Schedule Appointment') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
