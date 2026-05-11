<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Assignment #{{ $assignment->assignment_id }}</h2></x-slot>
    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-2">
            <p><strong>Staff:</strong> {{ $assignment->staff?->full_name }}</p>
            <p><strong>Patient:</strong> {{ $assignment->stay?->patient?->full_name }}</p>
            <p><strong>Role:</strong> {{ $assignment->role_description }}</p>
        </div>
    </div>
</x-app-layout>