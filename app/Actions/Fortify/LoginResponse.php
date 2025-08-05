<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class LoginResponse implements LoginResponseContract, RegisterResponseContract
{
    public function toResponse($request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}
