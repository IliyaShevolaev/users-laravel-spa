<?php

declare(strict_types=1);

namespace App\Http\Requests\Users;

use App\DTO\User\UserDTO;
use App\Enums\User\GenderEnum;
use App\Enums\User\StatusEnum;
use ClassTransformer\Hydrator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request для создания пользователя
 */
class CreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5|max:255|confirmed',
            'department_id' => 'nullable|int|exists:departments,id,deleted_at,NULL',
            'position_id' => 'nullable|int|exists:positions,id,deleted_at,NULL',
            'gender' => ['required', new Enum(GenderEnum::class)],
            'status' => ['required', new Enum(StatusEnum::class)]
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
            'name' => trans('main.users.name'),
        ];
    }
}
