<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Update Contract for') }} {{ $staff->first_name }} {{ $staff->last_name }}
            </h2>
            <a href="{{ route('staffs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                {{ __('Back to Directory') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        ✓ {{ session('success') }}
                    </div>
                @endif

                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="font-semibold text-gray-900 mb-2">{{ __('Important') }}</p>
                    <p class="text-sm text-gray-700">{{ __('Assigning a Charge Nurse position allows this staff member to be set as a ward\'s charge nurse. Only staff with active Charge Nurse contracts can be assigned as charge nurses to wards.') }}</p>
                </div>

                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="font-semibold text-gray-900 mb-3">{{ __('Current Contract') }}</p>
                    @if ($contract && $contract->position)
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-600 uppercase">{{ __('Position') }}</p>
                                <p class="font-medium text-gray-900">{{ $contract->position->position_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase">{{ __('Contract Type') }}</p>
                                <p class="font-medium text-gray-900">{{ $contract->contract_type === 'P' ? 'Permanent' : ($contract->contract_type === 'T' ? 'Temporary' : $contract->contract_type) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase">{{ __('Hours per Week') }}</p>
                                <p class="font-medium text-gray-900">{{ $contract->hours_per_week }} hours</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase">{{ __('Can be Charge Nurse') }}</p>
                                <p class="font-medium">
                                    @if($contract->position->position_name === 'Charge Nurse')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✓ Yes</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">✗ No</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-600 italic">{{ __('No contract assigned yet.') }}</p>
                    @endif
                </div>

                <form method="POST" action="{{ route('staffs.contract.update', $staff->staff_no) }}">
                    @csrf

                    <div class="grid gap-6">
                        <div>
                            <x-input-label for="position_id" :value="__('Job Position')" />
                            <select id="position_id" name="position_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">{{ __('Select a position') }}</option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->position_id }}" {{ old('position_id', $contract?->position_id) == $position->position_id ? 'selected' : '' }}
                                        data-is-charge-nurse="{{ $position->position_name === 'Charge Nurse' ? 'true' : 'false' }}">
                                        {{ $position->position_name }}
                                        @if($position->position_name === 'Charge Nurse')
                                            (can be ward charge nurse)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-sm text-gray-500 mt-2">{{ __('Select the job position for this staff member.') }}</p>
                        </div>

                        <div>
                            <x-input-label for="contract_type" :value="__('Contract Type')" />
                            <select id="contract_type" name="contract_type" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">{{ __('Select contract type') }}</option>
                                <option value="P" {{ old('contract_type', $contract?->contract_type) === 'P' ? 'selected' : '' }}>P - Permanent</option>
                                <option value="T" {{ old('contract_type', $contract?->contract_type) === 'T' ? 'selected' : '' }}>T - Temporary</option>
                                <option value="F" {{ old('contract_type', $contract?->contract_type) === 'F' ? 'selected' : '' }}>F - Fixed-term</option>
                            </select>
                            <p class="text-sm text-gray-500 mt-2">{{ __('Use: P for permanent, T for temporary, F for fixed-term contracts.') }}</p>
                        </div>

                        <div>
                            <x-input-label for="hours_per_week" :value="__('Hours per Week')" />
                            <x-text-input id="hours_per_week" class="block mt-1 w-full" type="number" step="0.25" min="0" max="40" name="hours_per_week" value="{{ old('hours_per_week', $contract?->hours_per_week) }}" required />
                            <p class="text-sm text-gray-500 mt-2">{{ __('Enter hours between 0 and 40 per week.') }}</p>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('staffs.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-500 transition">
                                {{ __('Save Contract') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
