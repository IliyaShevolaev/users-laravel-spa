<?php

namespace App\Http\Requests\Gallery;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateImageRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('images')->whereNull('deleted_at')->ignore($this->id)
            ],

            'image_file' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'file',
                'mimes:jpeg,jpg,png',
                'max:1000000',
            ],
        ];
    }
}
