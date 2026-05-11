<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admit Patient to Bed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('beds.storeAdmit') }}">
                        @csrf

                        <!-- Patient Stay Selection -->
                        <div class="mb-6">
                            <label for="stay_id" class="block font-medium text-sm text-gray-700 mb-2">
                                {{ __('Patient Stay') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="stay_id" id="stay_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required>
                                <option value="">-- Select Patient Stay --</option>
                                @foreach ($stays as $stay)
                                    <option value="{{ $stay->stay_id }}" {{ old('stay_id') == $stay->stay_id ? 'selected' : '' }}>
                                        Patient: {{ $stay->patient->first_name }} {{ $stay->patient->last_name }} (Stay ID: {{ $stay->stay_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('stay_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Bed Selection -->
                        <div class="mb-6">
                            <label for="bed_id" class="block font-medium text-sm text-gray-700 mb-2">
                                {{ __('Available Bed') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="bed_id" id="bed_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required>
                                <option value="">-- Select Bed --</option>
                                @foreach ($beds as $bed)
                                    <option value="{{ $bed->bed_id }}" {{ old('bed_id') == $bed->bed_id ? 'selected' : '' }}>
                                        Bed {{ $bed->bed_number }} in {{ $bed->ward->ward_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bed_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('beds.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Admit Patient') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>