<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Patient Medication') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('pharmaceuticals.store') }}">
                        @csrf

                        <!-- In-Patient Stay Selection -->
                        <div class="mb-6">
                            <label for="stay_id" class="block font-medium text-sm text-gray-700 mb-2">
                                {{ __('Patient (In-Patient Stay)') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="stay_id" id="stay_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required>
                                <option value="">-- Select Patient Stay --</option>
                                @foreach ($stays as $stay)
                                    <option value="{{ $stay->stay_id }}" {{ old('stay_id') == $stay->stay_id ? 'selected' : '' }}>
                                        {{ $stay->patient->first_name }} {{ $stay->patient->last_name }} (Stay ID: {{ $stay->stay_id }}) - Ward: {{ $stay->ward->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('stay_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Drug Selection -->
                        <div class="mb-6">
                            <label for="drug_no" class="block font-medium text-sm text-gray-700 mb-2">
                                {{ __('Medication') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="drug_no" id="drug_no" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required>
                                <option value="">-- Select Medication --</option>
                                @foreach ($pharmaceuticals as $drug)
                                    <option value="{{ $drug->drug_no }}" {{ old('drug_no') == $drug->drug_no ? 'selected' : '' }}>
                                        {{ $drug->supply->name }} - {{ $drug->dosage }} ({{ $drug->method_admin }})
                                    </option>
                                @endforeach
                            </select>
                            @error('drug_no')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Units Per Day -->
                        <div class="mb-6">
                            <label for="units_per_day" class="block font-medium text-sm text-gray-700 mb-2">
                                {{ __('Units Per Day') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="units_per_day" id="units_per_day" value="{{ old('units_per_day') }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" min="1" required />
                            @error('units_per_day')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Start Date -->
                        <div class="mb-6">
                            <label for="start_date" class="block font-medium text-sm text-gray-700 mb-2">
                                {{ __('Start Date') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required />
                            @error('start_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Finish Date -->
                        <div class="mb-6">
                            <label for="finish_date" class="block font-medium text-sm text-gray-700 mb-2">
                                {{ __('Finish Date') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="finish_date" id="finish_date" value="{{ old('finish_date') }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required />
                            @error('finish_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Add Medication') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
