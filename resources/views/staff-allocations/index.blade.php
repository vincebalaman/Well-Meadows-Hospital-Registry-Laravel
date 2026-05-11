<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Staff Ward Assignments</h2>
            <a href="{{ route('staff-allocations.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded">+ Assign Staff</a>
        </div>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-700">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Staff</th>
                            <th class="px-4 py-3">Ward</th>
                            <th class="px-4 py-3">Week Beginning</th>
                            <th class="px-4 py-3">Shift</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($allocations as $allocation)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $allocation->allocation_id }}</td>
                                <td class="px-4 py-3">{{ $allocation->staff?->full_name ?? $allocation->staff_no }}</td>
                                <td class="px-4 py-3">{{ $allocation->ward?->ward_name ?? $allocation->ward_id }}</td>
                                <td class="px-4 py-3">{{ optional($allocation->week_beginning)->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">{{ $allocation->shift_type }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">No ward assignments yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $allocations->links() }}</div>
        </div>
    </div>
</x-app-layout>
