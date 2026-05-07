<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Register New Patient') }}
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

                <form method="POST" action="{{ route('patients.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Patient Details</h3>
                            
                            <div>
                                <x-input-label for="patient_no" :value="__('Patient Number')" />
                                <x-text-input id="patient_no" class="block mt-1 w-full" type="text" name="patient_no" required placeholder="P10001" />
                            </div>

                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <x-input-label for="first_name" :value="__('First Name')" />
                                    <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" required />
                                </div>
                                <div class="w-1/2">
                                    <x-input-label for="last_name" :value="__('Last Name')" />
                                    <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" required />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="address" :value="__('Residential Address')" />
                                <textarea id="address" name="address" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="2" required></textarea>
                            </div>

                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <x-input-label for="tel_no" :value="__('Telephone Number')" />
                                    <x-text-input id="tel_no" class="block mt-1 w-full" type="text" name="tel_no" />
                                </div>
                                <div class="w-1/2">
                                    <x-input-label for="dob" :value="__('Date of Birth')" />
                                    <x-text-input id="dob" class="block mt-1 w-full" type="date" name="dob" required />
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <x-input-label for="sex" :value="__('Sex')" />
                                    <select id="sex" name="sex" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                        <option value="M">Male</option>
                                        <option value="F">Female</option>
                                    </select>
                                </div>
                                <div class="w-1/2">
                                    <x-input-label for="marital_status" :value="__('Marital Status')" />
                                    <select id="marital_status" name="marital_status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Widowed">Widowed</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <x-input-label for="clinic_no" :value="__('Assign Local Clinic')" />
                                <select id="clinic_no" name="clinic_no" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                    <option value="">Select a Clinic</option>
                                    @foreach($clinics as $clinic)
                                        <option value="{{ $clinic->clinic_no }}">
                                            {{ $clinic->full_name }} ({{ $clinic->clinic_no }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Next of Kin</h3>
                            
                            <div>
                                <x-input-label for="nok_name" :value="__('Full Name')" />
                                <x-text-input id="nok_name" class="block mt-1 w-full" type="text" name="nok_name" required />
                            </div>

                            <div>
                                <x-input-label for="nok_relationship" :value="__('Relationship')" />
                                <x-text-input id="nok_relationship" class="block mt-1 w-full" type="text" name="nok_relationship" required placeholder="e.g. Spouse, Parent" />
                            </div>

                            <div>
                                <x-input-label for="nok_tel" :value="__('Contact Number')" />
                                <x-text-input id="nok_tel" class="block mt-1 w-full" type="text" name="nok_tel" required />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t pt-4">
                        <x-primary-button class="ml-4">
                            {{ __('Register Patient & Next of Kin') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>