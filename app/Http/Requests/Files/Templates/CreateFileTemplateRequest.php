<?php

namespace App\Http\Requests\Files\Templates;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateFileTemplateRequest extends FormRequest
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
                Rule::unique('file_templates')->whereNull('deleted_at')->ignore($this->id)
            ],

            'file_template' => [
                'required',
                'file',
                'mimes:doc,docx,pdf',
                'max:1000000',
            ],
        ];
    }

    /**
     * Задает название возвращаемых атрибутов при ошибках валидации
     *
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return [
            'file_template' => trans('main.file_templates.file_template'),
        ];
    }
}
