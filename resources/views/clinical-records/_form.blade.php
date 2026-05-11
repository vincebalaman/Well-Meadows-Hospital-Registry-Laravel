@csrf
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Appointment</label>
        <select name="app_no" class="w-full mt-1 border-gray-300 rounded">
            <option value="">— Select —</option>
            @foreach ($appointments as $a)
                <option value="{{ $a->app_no }}"
                    @selected(old('app_no', $record->app_no ?? $preselected ?? '') === $a->app_no)>
                    {{ $a->app_no }} — {{ $a->patient?->full_name }} ({{ $a->app_date_time?->format('Y-m-d') }})
                </option>
            @endforeach
        </select>
        @error('app_no') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium">Diagnosis</label>
        <textarea name="diagnosis" rows="3" class="w-full mt-1 border-gray-300 rounded">{{ old('diagnosis', $record->diagnosis ?? '') }}</textarea>
        @error('diagnosis') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium">Treatment Plan</label>
        <textarea name="treatment_plan" rows="3" class="w-full mt-1 border-gray-300 rounded">{{ old('treatment_plan', $record->treatment_plan ?? '') }}</textarea>
        @error('treatment_plan') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium">Outcome</label>
        <select name="outcome" class="w-full mt-1 border-gray-300 rounded">
            <option value="">— Select —</option>
            @foreach ($outcomes as $o)
                <option value="{{ $o }}" @selected(old('outcome', $record->outcome ?? '') === $o)>{{ $o }}</option>
            @endforeach
        </select>
        @error('outcome') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
</div>
<div class="mt-6 flex gap-2">
    <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
    <a href="{{ route('clinical-records.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
</div>