<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    {{ __('Assigned Staff to Ward') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Manage ward names and prepare staff assignments.</p>
            </div>
            <a href="{{ route('wards.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 transition">
                {{ __('+ Add Ward') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Ward Name</th>
                                <th scope="col" class="px-6 py-3">Charge Nurse</th>
                                <th scope="col" class="px-6 py-3">Assignments</th>
                                <th scope="col" class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($wards as $ward)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $ward->ward_name }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($ward->chargeNurse)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $ward->chargeNurse->staff_no }} - {{ $ward->chargeNurse->first_name }} {{ $ward->chargeNurse->last_name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic text-xs">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">
                                            {{ $ward->allocations_count }} {{ $ward->allocations_count === 1 ? 'assignment' : 'assignments' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 space-x-3 text-sm">
                                        @if(in_array(auth()->user()->role, ['admin', 'staff']))
                                            <a href="{{ route('wards.assign-staff', $ward) }}" class="text-indigo-600 hover:underline">Assign</a>
                                        @endif
                                        <a href="{{ route('wards.edit', $ward) }}" class="text-blue-600 hover:underline">Edit</a>
                                        <a href="{{ route('wards.show', $ward) }}" class="text-indigo-600 hover:underline">View</a>
                                        <form action="{{ route('wards.destroy', $ward) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this ward?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-gray-500">No wards found. Add a new ward to begin.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
