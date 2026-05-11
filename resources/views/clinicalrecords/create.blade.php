<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Record Examination Outcome') }}
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

                <form method="POST" action="{{ route('clinicalrecords.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="app_no" :value="__('Appointment Number')" />
                                <select id="app_no" name="app_no" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                    <option value="">{{ __('Select appointment') }}</option>
                                    @foreach($appointments as $appointment)
                                        <option value="{{ $appointment->app_no }}" {{ (old('app_no') ?? $selectedAppNo ?? '') === $appointment->app_no ? 'selected' : '' }}>
                                            {{ $appointment->app_no }} — {{ optional($appointment->patient)->first_name }} {{ optional($appointment->patient)->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="diagnosis" :value="__('Diagnosis')" />
                                <textarea id="diagnosis" name="diagnosis" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="4" required>{{ old('diagnosis') }}</textarea>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <x-input-label for="treatment_plan" :value="__('Treatment Plan')" />
                                <textarea id="treatment_plan" name="treatment_plan" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="4" required>{{ old('treatment_plan') }}</textarea>
                            </div>

                            <div>
                                <x-input-label for="outcome" :value="__('Outcome')" />
                                <select id="outcome" name="outcome" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                    <option value="">{{ __('Select outcome') }}</option>
                                    <option value="Out-patient" {{ old('outcome') == 'Out-patient' ? 'selected' : '' }}>{{ __('Out-patient') }}</option>
                                    <option value="Wait-list" {{ old('outcome') == 'Wait-list' ? 'selected' : '' }}>{{ __('Wait-list') }}</option>
                                    <option value="Discharged" {{ old('outcome') == 'Discharged' ? 'selected' : '' }}>{{ __('Discharged') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t pt-4">
                        <x-primary-button class="ml-4">
                            {{ __('Save Outcome') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
