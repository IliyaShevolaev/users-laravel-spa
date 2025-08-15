<?php

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat', function () {
    return true;
});

Broadcast::channel('change.role.{roleId}', function (User $user, int $roleId) {
    Log::info('EROLE');
    return $user->roles()->first()->id === $roleId;
});
