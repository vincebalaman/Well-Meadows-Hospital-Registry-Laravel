<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Clinical Record #{{ $record->record_id }}</h2></x-slot>
    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-2">
            <p><strong>Appointment:</strong> {{ $record->app_no }}</p>
            <p><strong>Patient:</strong> {{ $record->appointment?->patient?->full_name }}</p>
            <p><strong>Diagnosis:</strong> {{ $record->diagnosis }}</p>
            <p><strong>Treatment Plan:</strong> {{ $record->treatment_plan }}</p>
            <p><strong>Outcome:</strong> {{ $record->outcome }}</p>
        </div>
    </div>
</x-app-layout>