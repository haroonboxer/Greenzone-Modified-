<?php

namespace App\Http\Requests\rms;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // You can modify this to check user permissions if needed.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_pa' => ['required', 'string', 'max:255'],
            'company_dr' => ['required', 'string', 'max:255'],
            'company_en' => ['required', 'string', 'max:255'],

        ];
    }

    /**
     * Customize the error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'company_pa.required' => 'The company PA field is required.',
            'company_dr.required' => 'The company DR field is required.',
            'company_en.required' => 'The company EN field is required.',
        ];
    }

    /**
     * Customize the attributes for validation messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'company_pa' => 'Company PA',
            'company_dr' => 'Company DR',
            'company_en' => 'Company EN',

        ];
    }
}
