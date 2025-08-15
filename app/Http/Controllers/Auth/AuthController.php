<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $user = Auth::user()?->load('roles');
        $permissions = $user?->getUserRolePermissionsCollection();

        return response()->json([
            'user' => $user,
            'permissions' => $permissions,
            'role' => $user?->roles()->first()
        ]);
    }
}
