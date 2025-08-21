<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

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
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
            'department_id' => 'nullable|int|exists:departments,id,deleted_at,NULL',
            'all_vision' => ['required', 'boolean']
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
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('all_vision') == false && !$this->input('department_id')) {
                $validator->errors()->add(
                    'department_id',
                    trans('validation.assigned_for')
                );
            }
        });
    }
}
