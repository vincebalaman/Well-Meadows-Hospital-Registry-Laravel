<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Populate Ward Beds') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('beds.storePopulate') }}">
                        @csrf

                        <!-- Ward Selection -->
                        <div class="mb-6">
                            <label for="ward_id" class="block font-medium text-sm text-gray-700 mb-2">
                                {{ __('Ward') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="ward_id" id="ward_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required>
                                <option value="">-- Select Ward --</option>
                                @foreach ($wards as $ward)
                                    <option value="{{ $ward->ward_id }}" {{ old('ward_id') == $ward->ward_id ? 'selected' : '' }}>
                                        {{ $ward->ward_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ward_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Number of Beds -->
                        <div class="mb-6">
                            <label for="num_to_add" class="block font-medium text-sm text-gray-700 mb-2">
                                {{ __('Number of Beds to Add') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="num_to_add" id="num_to_add" value="{{ old('num_to_add') }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" min="1" max="50" required />
                            @error('num_to_add')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('beds.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Populate Beds') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>