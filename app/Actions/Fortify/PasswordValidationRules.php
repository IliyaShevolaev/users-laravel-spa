<?php

declare(strict_types=1);

namespace App\Actions\Fortify;


trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', 'confirmed', 'max:255', 'min:3'];
    }
}
