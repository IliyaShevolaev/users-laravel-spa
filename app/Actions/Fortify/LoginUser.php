<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Http\Requests\LoginRequest;

class LoginUser
{
    /**
     * Попытка пользователя войти в систему
     * 
     * @param \Laravel\Fortify\Http\Requests\LoginRequest $request
     * @return User|null
     */
    public function __invoke(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            return $user;
        }

        return null;
    }
}
