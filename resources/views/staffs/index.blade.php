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
                                        <a href="{{ route('contracts.create', ['staff_no' => $staff->staff_no]) }}" class="text-indigo-600 hover:underline">
                                            {{ __('Contract') }}
                                        </a>
                                        @if(in_array(auth()->user()->role, ['admin']))
                                            <form action="{{ route('staffs.destroy', $staff->staff_no) }}" method="POST" onsubmit="return confirm('Remove this staff member?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 italic">No Actions</span>
                                        @endif
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