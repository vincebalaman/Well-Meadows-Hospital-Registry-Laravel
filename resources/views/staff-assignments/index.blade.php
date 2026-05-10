<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Staff–Patient Assignments</h2></x-slot>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            @if (session('success')) <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div> @endif
            <a href="{{ route('staff-assignments.create') }}" class="inline-block mb-4 px-4 py-2 bg-indigo-600 text-white rounded">+ Assign Staff</a>

            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50"><tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Staff</th>
                    <th class="px-4 py-2">Patient (Stay)</th>
                    <th class="px-4 py-2">Role</th>
                    <th class="px-4 py-2 text-right">Actions</th>
                </tr></thead>
                <tbody>
                    @forelse ($assignments as $a)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $a->assignment_id }}</td>
                            <td class="px-4 py-2">{{ $a->staff?->full_name }}</td>
                            <td class="px-4 py-2">{{ $a->stay?->patient?->full_name }} (#{{ $a->stay_id }})</td>
                            <td class="px-4 py-2">{{ $a->role_description }}</td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('staff-assignments.edit', $a) }}" class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('staff-assignments.destroy', $a) }}" method="POST" class="inline" onsubmit="return confirm('Remove?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No assignments yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $assignments->links() }}</div>
        </div>
    </div>
</x-app-layout>