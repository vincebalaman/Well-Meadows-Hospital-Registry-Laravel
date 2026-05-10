<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    {{ __('Assign Staff to Ward') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Use the database procedure to assign staff for the selected ward.</p>
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

                <form method="POST" action="{{ route('wards.assign-staff.store', $ward) }}">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="ward_name" :value="__('Ward')" />
                            <p class="mt-2 text-gray-700">{{ $ward->ward_name }}</p>
                        </div>

                        <div>
                            <x-input-label for="staff_no" :value="__('Staff Member')" />
                            <select id="staff_no" name="staff_no" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">{{ __('Select a staff member') }}</option>
                                @foreach ($staff as $member)
                                    <option value="{{ $member->staff_no }}" {{ old('staff_no') == $member->staff_no ? 'selected' : '' }}>
                                        {{ $member->staff_no }} - {{ $member->first_name }} {{ $member->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('wards.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                            <x-primary-button>
                                {{ __('Assign Staff') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
