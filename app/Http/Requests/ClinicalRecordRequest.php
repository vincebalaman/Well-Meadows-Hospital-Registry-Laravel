<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\ClinicalRecord;

class ClinicalRecordRequest extends FormRequest
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
            'app_no' => ['required', 'exists:appointments,app_no'],
            'diagnosis' => ['required', 'string'],
            'treatment_plan' => ['required', 'string'],
            'outcome' => ['required', Rule::in(ClinicalRecord::OUTCOMES)],
        ];
    }
}
