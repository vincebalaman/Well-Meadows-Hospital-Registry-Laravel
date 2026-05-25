<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Hospital Staff Directory') }}
            </h2>
            <a href="{{ route('staffs.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                {{ __('+ Add Staff Member') }}
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
                                <th scope="col" class="px-6 py-3 text-center">Staff No</th>
                                <th scope="col" class="px-6 py-3 text-center">Full Name</th>
                                <th scope="col" class="px-6 py-3 text-center">NIN</th>
                                <th scope="col" class="px-6 py-3 text-center">Sex</th>
                                <th scope="col" class="px-6 py-3 text-center">DOB</th>
                                <th scope="col" class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($staffMembers as $staff)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900 text-center">{{ $staff->staff_no }}</td>
                                    <td class="px-6 py-4 text-center">{{ $staff->first_name }} {{ $staff->last_name }}</td>
                                    <td class="px-6 py-4 text-center">{{ $staff->nin }}</td>
                                    <td class="px-6 py-4 text-center">{{ $staff->sex }}</td>
                                    <td class="px-6 py-4 text-center">{{ $staff->dob->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2 flex-wrap">
                                            <a href="{{ route('contracts.create', ['staff_no' => $staff->staff_no]) }}" class="btn-secondary flex items-center gap-1 px-3 py-1.5 text-xs">
                                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
                                                {{ __('Contract') }}
                                            </a>
                                            <a href="{{ route('staff-allocations.create', ['staff_no' => $staff->staff_no]) }}" class="btn-secondary flex items-center gap-1 px-3 py-1.5 text-xs">
                                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 0 0-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 0 1 5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 0 1 9.288 0M15 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm6 3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM7 10a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"/></svg>
                                                {{ __('Ward Assignment') }}
                                            </a>
                                            @if(in_array(auth()->user()->role, ['admin']))
                                                <form action="{{ route('staffs.destroy', $staff->staff_no) }}" method="POST" onsubmit="return confirm('Remove this staff member?');" class="inline">
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
