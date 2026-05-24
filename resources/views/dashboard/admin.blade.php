<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6 text-purple-800">Wellmeadows Hospital Registry — Enterprise Command Dashboard</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                        <span class="block text-gray-500 font-bold uppercase text-xs">Total Tracked Inpatients</span>
                        <span class="text-3xl font-extrabold text-blue-900">{{ $stats['total_patients'] }}</span>
                    </div>

                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                        <span class="block text-gray-500 font-bold uppercase text-xs">Staff Personnel Registered</span>
                        <span class="text-3xl font-extrabold text-green-900">{{ $stats['total_staff'] }}</span>
                    </div>

                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                        <span class="block text-gray-500 font-bold uppercase text-xs">Active Ward Beds Occupied</span>
                        <span class="text-3xl font-extrabold text-red-900">{{ $stats['occupied_beds'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>