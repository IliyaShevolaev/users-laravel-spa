<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return ['user' => Auth::user()];
});

Route::get('/auth-check', function (Request $request) {
    return ['auth' => Auth::check()];
});
