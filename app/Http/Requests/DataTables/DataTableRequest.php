<?php

namespace App\Http\Requests\DataTables;

use Illuminate\Foundation\Http\FormRequest;

class DataTableRequest extends FormRequest
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
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:-1', 'max:1000'],
            'sort_by' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'in:asc,desc'],
            'search' => ['nullable', 'string', 'max:255'],
            'draw' => ['nullable', 'integer'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'page' => $this->input('page') ?? 1,
            'per_page' => $this->input('per_page') ?? 10,
            'sort_order' => $this->input('sort_order') ? strtolower($this->input('sort_order')) : null,
        ]);
    }
}
