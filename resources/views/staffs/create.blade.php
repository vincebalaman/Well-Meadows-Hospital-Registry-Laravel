<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Register New Staff Member') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('staffs.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Identification</h3>
                            
                            <div>
                                <x-input-label for="staff_no" :value="__('Staff ID Number')" />
                                <x-text-input id="staff_no" class="block mt-1 w-full" type="text" name="staff_no" :value="old('staff_no')" required placeholder="S1000" />
                            </div>

                            <div>
                                <x-input-label for="nin" :value="__('National Insurance Number (NIN)')" />
                                <x-text-input id="nin" class="block mt-1 w-full" type="text" name="nin" :value="old('nin')" required placeholder="e.g., AB123456C" />
                            </div>

                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <x-input-label for="first_name" :value="__('First Name')" />
                                    <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required />
                                </div>
                                <div class="w-1/2">
                                    <x-input-label for="last_name" :value="__('Last Name')" />
                                    <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required />
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Additional Info</h3>

                            <div>
                                <x-input-label for="address" :value="__('Home Address')" />
                                <textarea id="address" name="address" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="2">{{ old('address') }}</textarea>
                            </div>

                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <x-input-label for="tel_no" :value="__('Phone Number')" />
                                    <x-text-input id="tel_no" class="block mt-1 w-full" type="text" name="tel_no" :value="old('tel_no')" />
                                </div>
                                <div class="w-1/2">
                                    <x-input-label for="dob" :value="__('Date of Birth')" />
                                    <x-text-input id="dob" class="block mt-1 w-full" type="date" name="dob" :value="old('dob')" required />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="sex" :value="__('Sex')" />
                                <select id="sex" name="sex" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>Male</option>
                                    <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t pt-4">
                        <x-primary-button>
                            {{ __('Register Staff Member') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>