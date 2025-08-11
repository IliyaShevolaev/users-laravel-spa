<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class LoginResponse implements LoginResponseContract, RegisterResponseContract
{
    /**
     * Вернуть авторизованного пользователя в виде json
     *
     * @param mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}
