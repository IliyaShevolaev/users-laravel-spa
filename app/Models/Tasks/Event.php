<?php

namespace App\Models\Tasks;

use App\Models\User;
use App\Policies\EventPolicy;
use App\Models\Tasks\EventUser;
use App\Models\User\Department;
use Spatie\Activitylog\LogOptions;
use App\Enums\Role\SystemRolesEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[UsePolicy(EventPolicy::class)]
class Event extends Model
{
    protected $fillable = ['title', 'description', 'start', 'end', 'creator_id'];

    /**
     * Получить отдел соыбтия
     *
     * @return HasOne<Department, $this>
     */
    public function department(): HasOne
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    /**
     * Получить пользователей с этой задачей
     *
     * @return BelongsToMany<User, Event, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function users(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'event_user')
            ->using(EventUser::class)
            ->withPivot('is_done', 'end_time')
            ->withTimestamps();
    }

    /**
     * Получить создателя этой задачи
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Event>
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Проверить может ли пользователь менять события в календаре
     *
     * @param User $user
     * @return bool
     */
    public function canUserChange(User $user)
    {
        $userRole = $user->roles()->first();
        if ($userRole->name === SystemRolesEnum::Admin->value) {
            return true;
        }

        $this->loadMissing('creator.roles');

        $creatorRole = $this->creator->roles()->first();
        if (
            $userRole->name === SystemRolesEnum::Manager->value &&
            $creatorRole->name !== SystemRolesEnum::Admin->value
        ) {
            return true;
        }

        return $this->creator_id === $user->id;
    }

    public function getIsDoneAttribute(): bool|null
    {
        return $this->pivot?->is_done;
    }

    public function getEndTimeAttribute(): ?string
    {
        return $this->pivot?->end_time ?? null;
    }

}
