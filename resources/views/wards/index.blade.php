<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ward Registry') }}
            </h2>
            <a href="{{ route('wards.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                {{ __('+ Register New Ward') }}
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
                    <table class="w-full text-sm text-center text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Ward ID</th>
                                <th scope="col" class="px-6 py-3">Ward Name</th>
                                <th scope="col" class="px-6 py-3">Location</th>
                                <th scope="col" class="px-6 py-3">Beds</th>
                                <th scope="col" class="px-6 py-3">Extn</th>
                                <th scope="col" class="px-6 py-3">Charge Nurse</th>
                                <th scope="col" class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($wards as $ward)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $ward->ward_id }}</td>
                                    <td class="px-6 py-4">{{ $ward->ward_name }}</td>
                                    <td class="px-6 py-4">{{ $ward->location }}</td>
                                    <td class="px-6 py-4">{{ $ward->total_beds }}</td>
                                    <td class="px-6 py-4">{{ $ward->tel_extn }}</td>
                                    <td class="px-6 py-4">
                                        @if($ward->chargeNurse)
                                            <span class="font-semibold text-gray-700">
                                                {{ $ward->chargeNurse->first_name }} {{ $ward->chargeNurse->last_name }}
                                            </span>
                                        @else
                                            <span class="text-red-400 italic">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center">
                                            @if(auth()->user()->role === 'admin')
                                                <form action="{{ route('wards.destroy', $ward->ward_id) }}" method="POST" onsubmit="return confirm('Delete this ward?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-danger flex items-center gap-1 px-3 py-1.5 text-xs">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
