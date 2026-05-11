<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('In-Patient Stays') }}
            </h2>
            <a href="{{ route('inpatientstays.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Request Ward Admission
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-md bg-green-50 border border-green-200">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 px-4 py-3 rounded-md bg-red-50 border border-red-200">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm text-red-800">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-sm text-center divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stay ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ward</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bed</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Placed</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expected Leave</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($stays as $stay)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $stay->stay_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $stay->patient->first_name }} {{ $stay->patient->last_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $stay->ward->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($stay->bed)
                                            {{ $stay->bed->bed_number }}
                                        @else
                                            <span class="text-gray-500 italic">Waiting</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $stay->status === 'discharged' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ ucfirst($stay->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $stay->date_placed_waiting }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $stay->expected_leave ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($stay->status !== 'discharged')
                                            <form action="{{ route('inpatientstays.discharge') }}" method="POST" onsubmit="return confirm('Are you sure you want to discharge this patient?');" class="inline">
                                                @csrf
                                                <input type="hidden" name="stay_id" value="{{ $stay->stay_id }}">
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">
                                                    Discharge
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500 italic">
                                        No in-patient stays recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($stays->hasPages())
                <div class="mt-4">
                    {{ $stays->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
