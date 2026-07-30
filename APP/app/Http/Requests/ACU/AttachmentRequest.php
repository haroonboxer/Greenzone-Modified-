<?php

namespace App\Http\Requests\ACU;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentRequest extends FormRequest
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
            'attachment.*' => 'required|mimes:jpeg,bmp,jpg,png|max:2000',
        ];
    }

    public function messages()
    {
        return [
            'attachment.*.required' => trans('global.attachmentMimes'),
            'attachment.*.mimes' => trans('global.attachmentMimes'),
            'attachment.*.max' => trans('global.attachmentSize'),
        ];
    }
}
