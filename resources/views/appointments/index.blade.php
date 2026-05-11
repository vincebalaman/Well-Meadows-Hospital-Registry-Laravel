<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Appointment Schedule') }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('appointments.medical_history') }}" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('View Medical History') }}
                </a>
                <a href="{{ route('appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    + {{ __('Schedule New Appointment') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-sm text-left divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Appointment #</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Consultant</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Date / Time</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Exam Room</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($appointments as $appointment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $appointment->app_no }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ optional($appointment->patient)->first_name }} {{ optional($appointment->patient)->last_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ optional($appointment->consultant)->first_name }} {{ optional($appointment->consultant)->last_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $appointment->app_date_time }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $appointment->exam_room ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-y-2">
                                        <a href="{{ route('clinicalrecords.create', ['app_no' => $appointment->app_no]) }}" class="text-green-600 hover:text-green-900">
                                            {{ __('Record Outcome') }}
                                        </a>
                                        @if(in_array(auth()->user()->role, ['admin']))
                                            <form action="{{ route('appointments.destroy', $appointment->app_no) }}" method="POST" onsubmit="return confirm('Remove this appointment?');" class="mt-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">{{ __('Delete') }}</button>
                                            </form>
                                        @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">
                                        {{ __('No appointments scheduled yet.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-6">
                        {{ $appointments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
