@csrf

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Staff Member</label>
        <select name="staff_no" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            <option value="">-- Select Staff --</option>
            @foreach ($staff as $member)
                <option value="{{ $member->staff_no }}"
                    @selected(old('staff_no', $selectedStaffNo ?? '') === $member->staff_no)>
                    {{ $member->staff_no }} — {{ $member->full_name }}
                </option>
            @endforeach
        </select>
        @error('staff_no') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Ward</label>
        <select name="ward_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            <option value="">-- Select Ward --</option>
            @foreach ($wards as $ward)
                <option value="{{ $ward->ward_id }}"
                    @selected((int) old('ward_id') === $ward->ward_id)>
                    {{ $ward->ward_name }} ({{ $ward->location }})
                </option>
            @endforeach
        </select>
        @error('ward_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Week Beginning</label>
        <input type="date" name="week_beginning" value="{{ old('week_beginning') }}"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        @error('week_beginning') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Shift</label>
        <select name="shift_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            <option value="">-- Select Shift --</option>
            <option value="Early" @selected(old('shift_type') === 'Early')>Early</option>
            <option value="Late" @selected(old('shift_type') === 'Late')>Late</option>
            <option value="Night" @selected(old('shift_type') === 'Night')>Night</option>
        </select>
        @error('shift_type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    @if ($errors->has('database_error'))
        <div class="p-4 bg-red-50 text-red-700 rounded">{{ $errors->first('database_error') }}</div>
    @endif

    <div class="flex justify-end gap-2 pt-4 border-t">
        <a href="{{ route('staff-allocations.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-black rounded">Save Assignment</button>
    </div>
</div>
