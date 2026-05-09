<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Staff Contract') }}: {{ $staff->first_name }} {{ $staff->last_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form action="{{ route('contracts.store') }}" method="POST">
                    @csrf
                    
                    <input type="hidden" name="staff_no" value="{{ $staff->staff_no }}">

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Job Position</label>
                            <select name="position_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">-- Select Position --</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position->position_id }}">
                                        {{ $position->position_name }} (Scale: {{ $position->salary_scale }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contract Type</label>
                            <select name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="P" {{ old('type') == 'P' ? 'selected' : '' }}>Permanent</option>
                                <option value="T" {{ old('type') == 'T' ? 'selected' : '' }}>Temporary</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hours per Week (Max 40)</label>
                            <input type="number" step="0.5" name="hours" max="40" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                                placeholder="e.g. 37.5" required>
                        </div>

                        <div class="flex items-center justify-end mt-4 pt-4 border-t">
                            <a href="{{ route('staffs.index') }}" class="text-sm text-gray-600 hover:underline mr-4">
                                {{ __('Cancel') }}
                            </a>
                            
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-indigo-700">
                                {{ __('Update Contract') }}
                            </button>
                        </div>
                    </div>
                </form>

                @if ($errors->any())
                    <div class="mt-6 p-4 bg-red-50 rounded-md">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>