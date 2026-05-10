<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    {{ __('Ward Details') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Review ward information and actions.</p>
            </div>
            <a href="{{ route('wards.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 transition">
                {{ __('Back to Wards') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $ward->ward_name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Ward ID: {{ $ward->ward_id }}</p>
                        <p class="text-sm text-gray-500 mt-2">Assignments: {{ $ward->allocations->count() }}</p>
                        <p class="text-sm text-gray-500 mt-2">
                            <span class="font-semibold">{{ __('Charge Nurse:') }}</span>
                            {{ optional($ward->chargeNurse)->staff_no ?? __('None') }}
                            @if(optional($ward->chargeNurse)->first_name)
                                - {{ optional($ward->chargeNurse)->first_name }} {{ optional($ward->chargeNurse)->last_name }}
                            @endif
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @if(in_array(auth()->user()->role, ['admin', 'staff']))
                            <a href="{{ route('wards.assign-staff', $ward) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-500 transition">
                                {{ __('Assign Staff') }}
                            </a>
                        @endif
                        <a href="{{ route('wards.edit', $ward) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 transition">
                            {{ __('Edit Ward') }}
                        </a>
                        <form action="{{ route('wards.destroy', $ward) }}" method="POST" onsubmit="return confirm('Delete this ward?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-500 transition">
                                {{ __('Delete Ward') }}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-10">
                    <h4 class="text-lg font-semibold text-gray-900">{{ __('Current Assignments') }}</h4>
                    <p class="text-sm text-gray-500 mt-1">Active staff assignments for this ward.</p>

                    @if($ward->allocations->isEmpty())
                        <div class="mt-6 rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-gray-600">
                            {{ __('No staff assignments have been created for this ward yet.') }}
                        </div>
                    @else
                        <div class="mt-6 overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
                            <table class="min-w-full text-left text-sm text-gray-600">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-700">
                                    <tr>
                                        <th class="px-6 py-3">{{ __('Staff') }}</th>
                                        <th class="px-6 py-3">{{ __('Week Beginning') }}</th>
                                        <th class="px-6 py-3">{{ __('Shift') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ward->allocations as $allocation)
                                        <tr class="border-t border-gray-100 hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                {{ optional($allocation->staff)->staff_no ?? '-' }}
                                                <div class="text-xs text-gray-500">{{ optional($allocation->staff)->first_name ?? '' }} {{ optional($allocation->staff)->last_name ?? '' }}</div>
                                            </td>
                                            <td class="px-6 py-4">{{ $allocation->week_beginning->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4">{{ $allocation->shift_type }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
