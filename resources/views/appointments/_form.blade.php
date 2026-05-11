@csrf
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Appointment No.</label>
        <input type="text" name="app_no" value="{{ old('app_no', $appointment->app_no ?? '') }}"
               @if(isset($appointment) && $appointment->exists) readonly @endif
               class="w-full mt-1 border-gray-300 rounded">
        @error('app_no') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Patient</label>
        <select name="patient_no" class="w-full mt-1 border-gray-300 rounded">
            <option value="">— Select —</option>
            @foreach ($patients as $p)
                <option value="{{ $p->patient_no }}"
                    @selected(old('patient_no', $appointment->patient_no ?? '') === $p->patient_no)>
                    {{ $p->patient_no }} — {{ $p->full_name }}
                </option>
            @endforeach
        </select>
        @error('patient_no') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Consultant</label>
        <select name="consultant_staff_no" class="w-full mt-1 border-gray-300 rounded">
            <option value="">— Select —</option>
            @foreach ($staff as $s)
                <option value="{{ $s->staff_no }}"
                    @selected(old('consultant_staff_no', $appointment->consultant_staff_no ?? '') === $s->staff_no)>
                    {{ $s->staff_no }} — {{ $s->full_name }}
                </option>
            @endforeach
        </select>
        @error('consultant_staff_no') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Date / Time</label>
        <input type="datetime-local" name="app_date_time"
               value="{{ old('app_date_time', isset($appointment->app_date_time) ? $appointment->app_date_time->format('Y-m-d\TH:i') : '') }}"
               class="w-full mt-1 border-gray-300 rounded">
        @error('app_date_time') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Exam Room</label>
        <input type="text" name="exam_room" maxlength="10"
               value="{{ old('exam_room', $appointment->exam_room ?? '') }}"
               class="w-full mt-1 border-gray-300 rounded">
        @error('exam_room') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    @if (auth()->user()->isStaff())
        <div>
            <label class="block text-sm font-medium">Status</label>
            <select name="status" class="w-full mt-1 border-gray-300 rounded">
                @foreach (\App\Models\Appointment::STATUSES as $status)
                    <option value="{{ $status }}"
                        @selected(old('status', $appointment->status ?? 'pending') === $status)>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            @error('status') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>
    @endif
</div>

<div class="mt-6 flex gap-2">
    <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save</button>
    <a href="{{ route('appointments.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
</div>