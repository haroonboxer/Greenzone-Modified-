<?php

namespace App\Http\Requests\rms;

use Illuminate\Foundation\Http\FormRequest;

class WeaponRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'number_of_weapons' => 'required|string|max:255',
            'slip_no' => 'required|string|max:255',
            'money_amount' => 'required|string|max:255',
            'slip_date' => 'required|date',
            'company_id' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'number_of_weapons.required' => 'Number of weapons is required.',
            'slip_no.required' => 'Slip number is required.',
            'money_amount.required' => 'Money amount is required.',
            'slip_date.required' => 'Slip date is required.',
            'slip_date.date' => 'Slip date must be a valid date.',
            'company_id.required' => 'Company selection is required.',
            'attachments.*.file' => 'Each attachment must be a valid file.',
            'attachments.*.mimes' => 'Attachments must be in jpg, jpeg, png, pdf, doc, or docx format.',
            'attachments.*.max' => 'Attachments must not be larger than 2MB.',
        ];
    }
}
