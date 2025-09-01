<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User;
use App\DTO\User\UserDTO;
use App\DTO\Roles\RoleDTO;
use App\Models\Roles\Role;
use App\DTO\User\CreateUserDTO;
use App\Models\Scopes\ActiveUserScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\User\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{

    public function all(): Collection
    {
        return User::where('name' , '!=', 'system')->get();
    }

    public function allWithRelations(): Collection
    {
        return User::withoutGlobalScope(ActiveUserScope::class)
            ->with(['department', 'position'])
            ->get();
    }

    public function allWithUnactive(): Collection
    {
        return User::withoutGlobalScope(ActiveUserScope::class)->get();
    }

    public function create(CreateUserDTO $dto): User
    {
        return User::create($dto->all());
    }

    public function update(int $userId, CreateUserDTO $dto): User
    {
        $user = User::withoutScopeFind($userId);
        $user->update($dto->all());

        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function find(int $userId): User
    {
        return User::withoutScopeFind($userId);
    }

    public function withoutScopeFind(int $userId): UserDTO
    {
        $user = User::withoutScopeFind($userId)
            ->load('roles');

        $roleDto = null;
        if ($user->roles->isNotEmpty()) {
            $roleDto = RoleDTO::from($user->roles->first());
        }

        return UserDTO::from(array_merge($user->toArray(), [
            'role' => $roleDto,
        ]));
    }

    public function getQueryWithRelations(array $relations): Builder
    {
        return User::withoutGlobalScope(ActiveUserScope::class)
        ->with($relations)
        ->select('users.*');
    }

    public function count(): int
    {
        return User::count();
    }

    public function getRelatedRole(int $userId)
    {
       $user = User::withoutScopeFind($userId);

       return $user->roles->first() ?? null;
    }
}
