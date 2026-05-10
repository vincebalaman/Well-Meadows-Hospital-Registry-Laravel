<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Edit Clinical Record</h2></x-slot>
    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form action="{{ route('clinical-records.update', $record) }}" method="POST">
                @method('PUT')
                @include('clinical-records._form', ['preselected' => null])
            </form>
        </div>
    </div>
</x-app-layout>