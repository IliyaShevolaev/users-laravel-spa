<?php

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('change.role.{roleId}', function (User $user, int $roleId) {
    return $user->roles()?->first()->id === $roleId;
});

Broadcast::channel('change.user.role.{userId}', function (User $user, int $userId) {
    return $user->id === $userId;
});
