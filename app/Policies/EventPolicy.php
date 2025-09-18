<?php

namespace App\Policies;

use App\DTO\Tasks\Event\CreateEventDTO;
use App\Enums\Role\SystemRolesEnum;
use App\Models\User;
use App\Models\Tasks\Event;
use Illuminate\Auth\Access\Response;

class EventPolicy
{

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user, CreateEventDTO $createEventDTO): bool
    {
        if (!$user->hasPermission('tasks-create')) {
            return false;
        }

        $userRoleName = $user->roles()->first()->name;
        $roleNamesMultyAssignAccess = collect([
            SystemRolesEnum::Admin->value,
            SystemRolesEnum::Manager->value
        ]);

        if ($roleNamesMultyAssignAccess->doesntContain($userRoleName) && count($createEventDTO->userId) > 1) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        if (!$user->hasPermission('tasks-update')) {
            return false;
        }

        return $event->canUserChange($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        if (!$user->hasPermission('tasks-delete')) {
            return false;
        }

        return $event->canUserChange($user);
    }
}
