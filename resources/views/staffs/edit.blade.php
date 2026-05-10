<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Staff Member') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('staffs.update', $staff->staff_no) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <x-input-label for="staff_no" :value="__('Staff Number')" />
                            <x-text-input id="staff_no" class="block mt-1 w-full" type="text" name="staff_no" :value="old('staff_no', $staff->staff_no)" required />
                            <x-input-error :messages="$errors->get('staff_no')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="first_name" :value="__('First Name')" />
                            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name', $staff->first_name)" required />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="last_name" :value="__('Last Name')" />
                            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $staff->last_name)" required />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="address" :value="__('Address')" />
                            <textarea id="address" name="address" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('address', $staff->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="tel_no" :value="__('Telephone Number')" />
                            <x-text-input id="tel_no" class="block mt-1 w-full" type="text" name="tel_no" :value="old('tel_no', $staff->tel_no)" />
                            <x-input-error :messages="$errors->get('tel_no')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="dob" :value="__('Date of Birth')" />
                            <x-text-input id="dob" class="block mt-1 w-full" type="date" name="dob" :value="old('dob', $staff->dob->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('dob')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="sex" :value="__('Sex')" />
                            <select id="sex" name="sex" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="M" {{ old('sex', $staff->sex) == 'M' ? 'selected' : '' }}>Male</option>
                                <option value="F" {{ old('sex', $staff->sex) == 'F' ? 'selected' : '' }}>Female</option>
                            </select>
                            <x-input-error :messages="$errors->get('sex')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="nin" :value="__('National Insurance Number')" />
                            <x-text-input id="nin" class="block mt-1 w-full" type="text" name="nin" :value="old('nin', $staff->nin)" required />
                            <x-input-error :messages="$errors->get('nin')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('staffs.index') }}" class="mr-4 underline text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <x-primary-button>
                                {{ __('Update Staff Member') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
