<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Patient Registry') }}
            </h2>
            <a href="{{ route('patients.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Register New Patient
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-sm text-center divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DOB</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sex</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clinic No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($patients as $patient)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $patient->patient_no }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $patient->first_name }} {{ $patient->last_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $patient->dob }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $patient->sex }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $patient->clinic_no }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if(in_array(auth()->user()->role, ['admin', 'staff']))
                                            <div class="flex items-center gap-2 justify-center">
                                                <a href="{{ route('patients.comprehensiveRecord', $patient->patient_no) }}" class="btn-secondary flex items-center gap-1 px-3 py-1.5 text-xs">
                                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12H9m12 0A9 9 0 1 1 3 12a9 9 0 0 1 18 0z"/></svg>
                                                    View
                                                </a>
                                                <a href="{{ route('pharmaceuticals.create') }}" class="btn-secondary flex items-center gap-1 px-3 py-1.5 text-xs">
                                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                                                    Add Meds
                                                </a>
                                                <form action="{{ route('patients.destroy', $patient->patient_no) }}" method="POST" onsubmit="return confirm('Are you sure? This will also remove the Next of Kin.');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-danger flex items-center gap-1 px-3 py-1.5 text-xs">
                                                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">
                                        No patients registered yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
