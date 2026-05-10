<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Edit Bill #{{ $bill->bill_id }}</h2></x-slot>
    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form action="{{ route('patient-billing.update', $bill) }}" method="POST">
                @method('PUT')
                @include('patient-billing._form')
            </form>
        </div>
    </div>
</x-app-layout>