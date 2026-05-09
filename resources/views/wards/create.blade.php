<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($ward) ? __('Update Ward') : __('Register New Ward') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form action="{{ route('wards.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ward ID (Numerical)</label>
                            <input type="number" name="ward_id" value="{{ old('ward_id', $ward->ward_id ?? '') }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            <p class="mt-1 text-xs text-gray-500">Manual ID entry to match legacy hospital records.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ward Name</label>
                            <input type="text" name="ward_name" value="{{ old('ward_name', $ward->ward_name ?? '') }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Location</label>
                                <input type="text" name="location" value="{{ old('location', $ward->location ?? '') }}" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tel Extension</label>
                                <input type="text" name="tel_extn" value="{{ old('tel_extn', $ward->tel_extn ?? '') }}" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Assign Charge Nurse</label>
                            <select name="charge_nurse_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">-- Select Qualified Staff --</option>
                                @foreach($chargeNurses as $nurse)
                                    <option value="{{ $nurse->staff_no }}" {{ (old('charge_nurse_id', $ward->charge_nurse_id ?? '') == $nurse->staff_no) ? 'selected' : '' }}>
                                        {{ $nurse->staff_no }} | {{ $nurse->first_name }} {{ $nurse->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-xs text-blue-600 italic">Only personnel with active 'Charge Nurse' contracts are available.</p>
                        </div>

                        <div class="flex items-center justify-end mt-4 pt-4 border-t">
                            <a href="{{ route('wards.index') }}" class="text-sm text-gray-600 hover:underline mr-4">Cancel</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-indigo-700">
                                {{ __('Save Ward Details') }}
                            </button>
                        </div>
                    </div>
                </form>

                @if ($errors->any())
                    <div class="mt-6 p-4 bg-red-50 rounded-md">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>