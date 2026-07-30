<?php

namespace App\Http\Requests\green_zone;

use Illuminate\Foundation\Http\FormRequest;

class vehicleRequest extends FormRequest
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
            'vehicle_type' => 'required|string|max:255',
            'vehicle_color' => 'required|string|max:255',
            'vehicle_platte_no' => 'required|string|max:255|unique:vehicles,vehicle_platte_no',
            'vehicle_engine_no' => 'required|string|max:255|unique:vehicles,vehicle_engine_no',
            'vehicle_source' => 'required|string|max:255',
            'front_photo' => 'required',
            'back_photo' => 'required',
            'plate_photo' => 'required',
            'attachments.*' => 'nullable|file|max:5120',
        ];
    }

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'vehicle_type.required' => 'Vehicle type is required.',
            'vehicle_color.required' => 'Vehicle color is required.',
            'vehicle_platte_no.required' => 'Plate number is required.',
            'vehicle_platte_no.unique' => 'This plate number is already registered.',
            'vehicle_engine_no.required' => 'Engine number is required.',
            'vehicle_engine_no.unique' => 'This engine number is already registered.',
            'vehicle_source.required' => 'Vehicle source is required.',
            'front_photo.required' => 'Front photo is required.',
            'front_photo.image' => 'Front photo must be an image.',
            'back_photo.required' => 'Back photo is required.',
            'plate_photo.required' => 'plate photo is required.',
            'back_photo.image' => 'Back photo must be an image.',
            'attachments.*.file' => 'Each attachment must be a file.',
            'attachments.*.max' => 'Each attachment must not exceed 5MB.',
        ];
    }
}
