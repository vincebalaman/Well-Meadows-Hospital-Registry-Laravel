<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4 text-green-700">Medical Staff Workspace</h2>

                @if(!$staffInfo)
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
                        <p class="font-bold">Profile Warning</p>
                        <p>Your operational active duty roster profile is incomplete.</p>
                        <a href="{{ route('staffs.create') }}" class="mt-2 inline-block bg-green-600 text-black px-4 py-2 rounded">Register Staff Details</a>
                    </div>
                @else
                    <div class="border border-gray-200 rounded p-4">
                        <p><strong>Employee Matrix ID:</strong> {{ $staffInfo->staff_no }}</p>
                        <p><strong>Employee First Name:</strong> {{ $staffInfo->first_name}}</p>
                        <p><strong>Employee Last Name:</strong> {{ $staffInfo->last_name}}</p>
                        <div class="mt-4 p-2 bg-gray-50 border border-gray-300 rounded text-sm text-gray-600">
                            *Secure Staff Authorization context active. Administrative procedures accessible via internal navigation links.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>