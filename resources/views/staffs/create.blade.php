<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Register New Staff Member') }}
        </h2>
    </x-slot>

    <div class="bg-gray-900 min-h-screen py-12">
        <div class="max-w-4xl mx-auto bg-gray-800 shadow rounded-lg p-8 text-gray-100">

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-700 bg-opacity-20 border border-red-500 text-red-400 rounded">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('staffs.store') }}">
                @csrf

                {{-- Identification Section --}}
                <h2 class="text-xl font-semibold mb-6 border-b border-gray-700 pb-2">Identification</h2>

                <div class="space-y-5">
                    <div>
                        <x-input-label for="staff_no" :value="__('Staff ID Number')" class="text-black-300" />
                        <x-text-input id="staff_no" class="block mt-1 w-full bg-gray-700 border-gray-600 text-gray-100 focus:ring-2 focus:ring-blue-500"
                                      type="text" name="staff_no" :value="old('staff_no')" required placeholder="S1000" />
                    </div>

                    <div>
                        <x-input-label for="nin" :value="__('National Insurance Number (NIN)')" class="text-black-300" />
                        <x-text-input id="nin" class="block mt-1 w-full bg-gray-700 border-gray-600 text-gray-100 focus:ring-2 focus:ring-blue-500"
                                      type="text" name="nin" :value="old('nin')" required placeholder="e.g., AB123456C" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="first_name" :value="__('First Name')" class="text-black-300" />
                            <x-text-input id="first_name" class="block mt-1 w-full bg-gray-700 border-gray-600 text-gray-100 focus:ring-2 focus:ring-blue-500"
                                          type="text" name="first_name" :value="old('first_name')" required />
                        </div>

                        <div>
                            <x-input-label for="last_name" :value="__('Last Name')" class="text-black-300" />
                            <x-text-input id="last_name" class="block mt-1 w-full bg-gray-700 border-gray-600 text-gray-100 focus:ring-2 focus:ring-blue-500"
                                          type="text" name="last_name" :value="old('last_name')" required />
                        </div>
                    </div>
                </div>

                {{-- Additional Info Section --}}
                <h2 class="text-xl font-semibold mt-10 mb-6 border-b border-gray-700 pb-2">Additional Info</h2>

                <div class="space-y-5">
                    <div>
                        <x-input-label for="address" :value="__('Home Address')" class="text-black-300" />
                        <textarea id="address" name="address"
                                  class="block mt-1 w-full bg-gray-700 border-gray-600 text-gray-100 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                                  rows="2">{{ old('address') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="tel_no" :value="__('Phone Number')" class="text-black-300" />
                            <x-text-input id="tel_no" class="block mt-1 w-full bg-gray-700 border-gray-600 text-gray-100 focus:ring-2 focus:ring-blue-500"
                                          type="text" name="tel_no" :value="old('tel_no')" />
                        </div>
                        <div>
                            <x-input-label for="dob" :value="__('Date of Birth')" class="text-gray-300" />
                            <x-text-input id="dob" class="block mt-1 w-full bg-gray-700 border-gray-600 text-gray-100 focus:ring-2 focus:ring-blue-500"
                                          type="date" name="dob" :value="old('dob')" required />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="sex" :value="__('Sex')" class="text-gray-300" />
                        <select id="sex" name="sex"
                                class="block mt-1 w-full bg-gray-700 border-gray-600 text-gray-100 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>Male</option>
                            <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-10 border-t border-gray-700 pt-6">
                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-semibold">
                        {{ __('Register Staff Member') }}
                    </x-primary-button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
