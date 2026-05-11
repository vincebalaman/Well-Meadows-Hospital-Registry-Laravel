<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StaffPatientAssignmentRequest extends FormRequest
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
        return [
            'staff_no' => ['required', 'exists:staff,staff_no'],
            'stay_id' => ['required', 'exists:in_patient_stays,stay_id'],
            'role_description' => ['required', 'string', 'max:100'],
        ];
    }
}
