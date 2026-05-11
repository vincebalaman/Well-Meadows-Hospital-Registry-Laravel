@csrf
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Patient Stay</label>
        <select name="stay_id" class="w-full mt-1 border-gray-300 rounded" required>
            <option value="">— Select —</option>
            @foreach ($stays as $stay)
                <option value="{{ $stay->stay_id }}">
                    Stay #{{ $stay->stay_id }} — {{ $stay->patient?->full_name }}
                </option>
            @endforeach
        </select>
        @error('stay_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-6 flex gap-2">
    <button class="px-4 py-2 bg-indigo-600 text-black rounded hover:bg-indigo-700">Generate Bill</button>
    <a href="{{ route('patientbillings.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
</div>