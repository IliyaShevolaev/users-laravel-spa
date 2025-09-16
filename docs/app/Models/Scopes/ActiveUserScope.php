<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Models\User;
use App\Enums\User\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

/**
 * Scope для получения только пользователей с активным статусом
 */
class ActiveUserScope implements Scope
{
       /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder<User> $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('status', StatusEnum::Active->value);
    }
}
