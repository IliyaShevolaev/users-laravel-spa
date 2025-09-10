<?php

namespace App\Http\Requests\Files\Templates;

use Illuminate\Foundation\Http\FormRequest;

class GenereteFileWithTemplateRequest extends FormRequest
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
            'template_id' => ['required', 'int'],
            'user_id' => ['required', 'int'],
            'format' => ['required', 'string'],
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
            'format' => trans('main.file_templates.format'),
            'user_id' => trans('main.file_templates.user_id'),
        ];
    }
}
