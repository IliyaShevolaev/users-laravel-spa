<?php

namespace App\Http\Controllers\Auth;

use App\Enums\User\GenderEnum;
use App\Http\Controllers\Controller;
use App\Services\Auth\Service;

class RegisterController extends Controller
{
    public function __construct(private Service $service)
    {
    }

    public function create()
    {
        $preparedData = $this->service->prepareRegisterData();

        return response()->json($preparedData);
    }
}
