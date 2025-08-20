<?php

namespace App\Policies;

use App\DTO\Tasks\Event\CreateEventDTO;
use App\Models\User;
use App\Models\Tasks\Event;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create the model.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('tasks-createDepartment') || $user->hasPermission('tasks-createAll');
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(User $user, CreateEventDTO $createEventDTO): bool
    {
        debugbar()->info('in permissions');

        if (!($user->hasPermission('tasks-createDepartment') || $user->hasPermission('tasks-createAll'))) {
            return false;
        }

        if ($createEventDTO->allVision && !$user->hasPermission('tasks-createAll')) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        return false;
    }
}
