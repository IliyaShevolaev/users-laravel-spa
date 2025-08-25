<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreateEventRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:65535'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
            'user_id' => ['required', 'array', 'min:1'],
            'user_id.*' => ['required'],
            'creator_id' => ['required', 'int']
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
            'title' => trans('main.title'),
            'start' => trans('main.date_range'),
            'end' => trans('main.date_range'),
            'user_id.*' => trans('main.must_user_assign')
        ];
    }
}
