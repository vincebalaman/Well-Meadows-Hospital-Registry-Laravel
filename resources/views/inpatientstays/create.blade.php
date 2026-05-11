<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Request Ward Admission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('inpatientstays.store') }}">
                        @csrf

                        <!-- Patient Selection -->
                        <div class="mb-6">
                            <label for="patient_no" class="block font-medium text-sm text-gray-700 mb-2">
                                {{ __('Patient') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="patient_no" id="patient_no" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required>
                                <option value="">-- Select Patient --</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->patient_no }}" {{ old('patient_no') == $patient->patient_no ? 'selected' : '' }}>
                                        {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_no }})
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_no')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Ward Selection -->
                        <div class="mb-6">
                            <label for="ward_id" class="block font-medium text-sm text-gray-700 mb-2">
                                {{ __('Ward') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="ward_id" id="ward_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required>
                                <option value="">-- Select Ward --</option>
                                @foreach ($wards as $ward)
                                    <option value="{{ $ward->ward_id }}" {{ old('ward_id') == $ward->ward_id ? 'selected' : '' }}>
                                        {{ $ward->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ward_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Expected Duration -->
                        <div class="mb-6">
                            <label for="expected_duration" class="block font-medium text-sm text-gray-700 mb-2">
                                {{ __('Expected Duration (Days)') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="expected_duration" id="expected_duration" value="{{ old('expected_duration') }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" min="1" required />
                            @error('expected_duration')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('inpatientstays.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Request Admission') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
