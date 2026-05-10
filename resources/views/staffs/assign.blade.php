<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Assign Ward for') }} {{ $staff->first_name }} {{ $staff->last_name }}
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

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        ✓ {{ session('success') }}
                    </div>
                @endif

                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="font-semibold text-gray-900 mb-2">{{ __('Staff Contract Information') }}</p>
                    @if($staff->contract && $staff->contract->position)
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <p class="text-xs text-gray-600 uppercase">{{ __('Position') }}</p>
                                <p class="font-medium text-gray-900">{{ $staff->contract->position->position_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase">{{ __('Hours/Week') }}</p>
                                <p class="font-medium text-gray-900">{{ $staff->contract->hours_per_week }} hrs</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase">{{ __('Contract Type') }}</p>
                                <p class="font-medium text-gray-900">{{ $staff->contract->contract_type === 'P' ? 'Permanent' : ($staff->contract->contract_type === 'T' ? 'Temporary' : $staff->contract->contract_type) }}</p>
                            </div>
                        </div>
                    @else
                        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded">
                            <p class="text-sm text-yellow-800">{{ __('⚠️ Warning: No contract assigned to this staff member yet.') }}</p>
                            <a href="{{ route('staffs.contract.edit', $staff->staff_no) }}" class="text-yellow-700 hover:text-yellow-900 font-medium text-sm mt-2 inline-block">
                                {{ __('Assign a contract first') }} →
                            </a>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('staffs.assign-ward.store', $staff->staff_no) }}">
                    @csrf

                    <div class="grid gap-6">
                        <div>
                            <x-input-label for="ward_id" :value="__('Ward')" />
                            <select id="ward_id" name="ward_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">{{ __('Select a ward') }}</option>
                                @foreach ($wards as $ward)
                                    <option value="{{ $ward->ward_id }}" {{ old('ward_id') == $ward->ward_id ? 'selected' : '' }}>{{ $ward->ward_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="week_beginning" :value="__('Week Beginning')" />
                            <x-text-input id="week_beginning" class="block mt-1 w-full" type="date" name="week_beginning" value="{{ old('week_beginning') }}" required />
                        </div>

                        <div>
                            <x-input-label for="shift_type" :value="__('Shift Type')" />
                            <select id="shift_type" name="shift_type" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">{{ __('Choose a shift') }}</option>
                                <option value="Early" {{ old('shift_type') == 'Early' ? 'selected' : '' }}>{{ __('Early') }}</option>
                                <option value="Late" {{ old('shift_type') == 'Late' ? 'selected' : '' }}>{{ __('Late') }}</option>
                                <option value="Night" {{ old('shift_type') == 'Night' ? 'selected' : '' }}>{{ __('Night') }}</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                                {{ __('Assign Ward') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
