@csrf
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Staff Member</label>
        <select name="staff_no" class="w-full mt-1 border-gray-300 rounded">
            <option value="">— Select —</option>
            @foreach ($staff as $s)
                <option value="{{ $s->staff_no }}"
                    @selected(old('staff_no', $assignment->staff_no ?? '') === $s->staff_no)>
                    {{ $s->staff_no }} — {{ $s->full_name }}
                </option>
            @endforeach
        </select>
        @error('staff_no') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium">Patient Stay</label>
        <select name="stay_id" class="w-full mt-1 border-gray-300 rounded">
            <option value="">— Select —</option>
            @foreach ($stays as $stay)
                <option value="{{ $stay->stay_id }}"
                    @selected((int) old('stay_id', $assignment->stay_id ?? 0) === $stay->stay_id)>
                    Stay #{{ $stay->stay_id }} — {{ $stay->patient?->full_name }}
                </option>
            @endforeach
        </select>
        @error('stay_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium">Role</label>
        <input type="text" name="role_description" maxlength="100"
               value="{{ old('role_description', $assignment->role_description ?? '') }}"
               placeholder="e.g. Primary Nurse, Physical Therapist"
               class="w-full mt-1 border-gray-300 rounded">
        @error('role_description') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
</div>
<div class="mt-6 flex gap-2">
    <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
    <a href="{{ route('staff-assignments.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
</div>