<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Appointments</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                @endif

                <a href="{{ route('appointments.create') }}"
                   class="inline-block mb-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    + Schedule Appointment
                </a>

                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-2">App No.</th>
                            <th class="px-4 py-2">Patient</th>
                            <th class="px-4 py-2">Consultant</th>
                            <th class="px-4 py-2">Date / Time</th>
                            <th class="px-4 py-2">Room</th>
                            <th class="px-4 py-2">Status</th>
                            @if (auth()->user()->isStaff())
                                <th class="px-4 py-2 text-right">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointments as $appointment)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $appointment->app_no }}</td>
                                <td class="px-4 py-2">{{ $appointment->patient?->full_name ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $appointment->consultant?->full_name ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $appointment->app_date_time?->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-2">{{ $appointment->exam_room ?? '—' }}</td>
                                <td class="px-4 py-2">
                                    @php
                                        $statusClass = match($appointment->status) {
                                            'approved' => 'bg-green-100 text-green-800',
                                            'pending'  => 'bg-yellow-100 text-yellow-800',
                                            default    => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusClass }}">
                                        {{ ucfirst($appointment->status ?? 'pending') }}
                                    </span>
                                </td>
                                @if (auth()->user()->isStaff())
                                    <td class="px-4 py-2 text-right space-x-2">
                                        <a href="{{ route('appointments.show', $appointment) }}" class="text-indigo-600 hover:underline">View</a>
                                        <a href="{{ route('appointments.edit', $appointment) }}" class="text-blue-600 hover:underline">Edit</a>
                                        <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Cancel this appointment?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->isStaff() ? 7 : 6 }}" class="px-4 py-6 text-center text-gray-500">
                                    No appointments yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $appointments->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>