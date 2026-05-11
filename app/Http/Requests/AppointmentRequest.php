<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $appNo = $this->route('appointment')?->app_no;
        
        return [
            'app_no' => [
                'required', 'string', 'max:15',
                'unique:appointments,app_no' . ($appNo ? ",{$appNo},app_no" : ''),
            ],
            'patient_no' => ['required', 'exists:patients,patient_no'],
            'consultant_staff_no' => ['required', 'exists:staff,staff_no'],
            'app_date_time' => ['required', 'date', 'after_or_equal:today'],
            'exam_room' => ['nullable', 'string', 'max:10'],
        ];
    }
}
