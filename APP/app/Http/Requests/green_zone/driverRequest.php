<?php

namespace App\Http\Requests\green_zone;

use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
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
            'name'              => ['required', 'string', 'max:255'],
            'f_name'            => ['required', 'string', 'max:255'],
            'g_f_name'          => ['required', 'string', 'max:255'],
            'nic'               => ['required', 'string', 'max:255'],
            'phone'             => ['required', 'string', 'max:255'],
            'photo'             => ['required', 'file', 'mimes:jpeg,png,jpg'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'             => 'The driver name is required.',
            'f_name.required'           => 'The father’s name is required.',
            'g_f_name.required'         => 'The grandfather’s name is required.',
            'nic.required'              => 'The NIC / Passport number is required.',
            'phone.required'            => 'The phone number is required.',
            'photo.required'            => 'The photo field is required.',

        ];
    }

    public function attributes(): array
    {
        return [
            'name'              => 'Driver Name',
            'f_name'            => 'Father’s Name',
            'g_f_name'          => 'Grandfather’s Name',
            'nic'               => 'NIC / Passport No',
            'phone'             => 'Phone Number',
            'photo'             => 'Photo',
        ];
    }
}
