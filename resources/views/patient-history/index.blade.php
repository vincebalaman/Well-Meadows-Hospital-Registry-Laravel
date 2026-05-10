<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Patient Treatment History</h2></x-slot>
    <div class="py-12 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <p class="mb-4 text-gray-600">Select a patient to view their full treatment history.</p>
            <ul class="divide-y">
                @forelse ($patients as $p)
                    <li class="py-2 flex justify-between items-center">
                        <span>{{ $p->patient_no }} — {{ $p->full_name }}</span>
                        <a href="{{ route('patient-history.show', $p) }}" class="text-indigo-600 hover:underline">View history →</a>
                    </li>
                @empty
                    <li class="py-2 text-gray-500">No patients registered yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-app-layout>