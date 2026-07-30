<?php

namespace App\Http\Requests\rms;
use Illuminate\Foundation\Http\FormRequest;

class AssistantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_dr' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'last_name_dr' => 'required|string|max:255',
            'last_name_en' => 'nullable|string|max:255',
            'f_name_da' => 'nullable|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:10',
            'passport_no' => 'nullable|string|max:50',
            'country' => 'required|string|max:100',
            // 'photo' => 'nullable|mimes:jpeg,png,jpg,gif|max:3500',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,pdf,docx,gif|max:3500',

            'main_province' => 'nullable|string|max:255',
            'main_district' => 'nullable|string|max:255',
            'main_village' => 'nullable|string|max:255',
            'current_province' => 'nullable|string|max:255',
            'current_district' => 'nullable|string|max:255',
            'current_village' => 'nullable|string|max:255',
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes([
            'main_province',
            'main_district',
            'main_village',
            'current_province',
            'current_district',
            'current_village',
        ], 'required', function ($input) {
            return $input->country === 'Afghanistan' || $input->country === 'افغانستان';
        });
    }

    public function messages(): array
    {
        return [
            'main_province.required' => 'Main province is required when country is Afghanistan.',
            'main_district.required' => 'Main district is required when country is Afghanistan.',
            'main_village.required' => 'Main village is required when country is Afghanistan.',
            'current_province.required' => 'Current province is required when country is Afghanistan.',
            'current_district.required' => 'Current district is required when country is Afghanistan.',
            'current_village.required' => 'Current village is required when country is Afghanistan.',
        ];
    }
}
