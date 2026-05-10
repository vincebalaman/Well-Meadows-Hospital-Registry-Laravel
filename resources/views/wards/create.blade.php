<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    {{ __('Add Ward') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Create a new ward for staff assignment.</p>
            </div>
            <a href="{{ route('wards.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 transition">
                {{ __('Back to Wards') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('wards.store') }}">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="ward_name" :value="__('Ward Name')" />
                            <x-text-input id="ward_name" class="block mt-1 w-full" type="text" name="ward_name" :value="old('ward_name')" required autofocus />
                            <x-input-error :messages="$errors->get('ward_name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="charge_nurse_id" :value="__('Charge Nurse')" />
                            <select id="charge_nurse_id" name="charge_nurse_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">{{ __('None / select charge nurse') }}</option>
                                @foreach($chargeNurses as $nurse)
                                    <option value="{{ $nurse->staff_no }}" {{ old('charge_nurse_id') == $nurse->staff_no ? 'selected' : '' }}>
                                        {{ $nurse->staff_no }} - {{ $nurse->first_name }} {{ $nurse->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('charge_nurse_id')" class="mt-2" />
                            <p class="text-sm text-gray-500 mt-1">{{ __('Select a staff member with a Charge Nurse contract.') }}</p>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('wards.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                            <x-primary-button>
                                {{ __('Create Ward') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
