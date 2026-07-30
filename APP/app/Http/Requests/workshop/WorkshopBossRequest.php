<?php

namespace App\Http\Requests\workshop;

use Illuminate\Foundation\Http\FormRequest;

class WorkshopBossRequest extends FormRequest
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
            'name_dr' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'last_name_dr' => ['required', 'string', 'max:255'],
            'last_name_en' => ['required', 'string', 'max:255'],
            'f_name_da' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'photo' => ['required', 'file', 'mimes:jpeg,png,jpg'],
            'passport_no' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'main_province' => ['nullable', 'string', 'max:255'],
            'main_district' => ['nullable', 'string', 'max:255'],
            'main_village' => ['nullable', 'string', 'max:255'],
            'current_province' => ['nullable', 'string', 'max:255'],
            'current_district' => ['nullable', 'string', 'max:255'],
            'current_village' => ['nullable', 'string', 'max:255'],
            // 'attachments' => ['required', 'string', 'max:255'], // Changed to string as in migration
            // 'status' => ['nullable', 'integer'],
            // 'created_by' => ['required', 'exists:users,id'],
            // 'created_department' => ['required', 'exists:departments,id'],
            // 'created_location' => ['required', 'exists:provinces,id'],
        ];
    }
    public function messages(): array
    {
        return [
            'name_dr.required' => 'The name in Dari is required.',
            'name_en.required' => 'The name in English is required.',
            'last_name_dr.required' => 'The last name in Dari is required.',
            'last_name_en.required' => 'The last name in English is required.',
            'f_name_da.required' => 'The father’s name is required.',
            'phone.required' => 'The phone number is required.',
            'photo.required' => 'The photo filed is required.',
            'passport_no.required' => 'The passport number is required.',
            'country.required' => 'The country field is required.',
            // 'main_province.required' => 'The main province is required.',
            // 'main_district.required' => 'The main district is required.',
            // 'main_village.required' => 'The main village is required.',
            // 'current_province.required' => 'The current province is required.',
            // 'current_district.required' => 'The current district is required.',
            // 'current_village.required' => 'The current village is required.',
            // 'attachments.required' => 'The attachments field is required.',
            // 'status.integer' => 'The status must be an integer.',
            // 'created_by.exists' => 'The created by user must exist.',
            // 'created_department.exists' => 'The created department must exist.',
            // 'created_location.exists' => 'The created location must exist.',
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
            'name_dr' => 'Name in Dari',
            'name_en' => 'Name in English',
            'last_name_dr' => 'Last Name in Dari',
            'last_name_en' => 'Last Name in English',
            'f_name_da' => 'Father’s Name',
            'phone' => 'Phone Number',
            'photo' => 'Photo',
            'passport_no' => 'Passport Number',
            'country' => 'Country',
            'main_province' => 'Main Province',
            // 'main_district' => 'Main District',
            // 'main_village' => 'Main Village',
            // 'current_province' => 'Current Province',
            // 'current_district' => 'Current District',
            // 'current_village' => 'Current Village',
            // 'attachments' => 'Attachments',
            // 'status' => 'Status',
            // 'created_by' => 'Created By',
            // 'created_department' => 'Created Department',
            // 'created_location' => 'Created Location',
        ];
    }
}
