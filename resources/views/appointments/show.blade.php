<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Appointment {{ $appointment->app_no }}</h2></x-slot>
    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-2">
            <p><strong>Patient:</strong> {{ $appointment->patient?->full_name }} ({{ $appointment->patient_no }})</p>
            <p><strong>Consultant:</strong> {{ $appointment->consultant?->full_name }} ({{ $appointment->consultant_staff_no }})</p>
            <p><strong>Date / Time:</strong> {{ $appointment->app_date_time?->format('Y-m-d H:i') }}</p>
            <p><strong>Room:</strong> {{ $appointment->exam_room ?? '—' }}</p>
            <p><strong>Status:</strong>
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
            </p>
        </div>

        @if (auth()->user()->isStaff())
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-2">Clinical Outcome</h3>
                @if ($appointment->clinicalRecord)
                    <p><strong>Diagnosis:</strong> {{ $appointment->clinicalRecord->diagnosis }}</p>
                    <p><strong>Treatment:</strong> {{ $appointment->clinicalRecord->treatment_plan }}</p>
                    <p><strong>Outcome:</strong> {{ $appointment->clinicalRecord->outcome }}</p>
                @else
                    <a href="{{ route('clinical-records.create', ['app_no' => $appointment->app_no]) }}"
                       class="text-indigo-600 hover:underline">+ Record outcome</a>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>