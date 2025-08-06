<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Enums\User\GenderEnum;

/**
 * Сервис для автризации
 */
class Service
{
    public function prepareRegisterData(): array
    {
        $genderArray = [];

        foreach (GenderEnum::cases() as $genderValue) {
            array_push($genderArray, [
                'text' => trans('main.users.genders.' . $genderValue->value),
                'value' => $genderValue->value
            ]);
        }

        return ['genders' => $genderArray];
    }
}
