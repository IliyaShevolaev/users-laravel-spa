<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User;
use App\DTO\User\UserDTO;
use App\DTO\User\CreateUserDTO;
use App\Models\Scopes\ActiveUserScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\User\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{

    public function all(): Collection
    {
        return User::all();
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

    public function delete(int $userId): void
    {
        $user = User::withoutScopeFind($userId);
        $user->delete();
    }

    public function find(int $userId): User
    {
        return User::findOrFail($userId);
    }

    public function withoutScopeFind(int $userId): UserDTO
    {
        return UserDTO::from(User::withoutScopeFind($userId)->toArray());
    }


    public function getQueryWithRelations(): Builder
    {
        return User::withoutGlobalScope(ActiveUserScope::class)
        ->with(['department', 'position', 'roles'])
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
