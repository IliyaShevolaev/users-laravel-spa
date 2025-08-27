<?php

namespace App\Models\Tasks;

use App\Models\User;
use App\Policies\EventPolicy;
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
    use LogsActivity;

    protected $fillable = ['title', 'description', 'start', 'end', 'creator_id'];
    protected static $recordEvents = ['deleted'];

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
        return $this->belongsToMany(User::class, 'event_user')->withPivot('is_done');
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['creator_id']);
    }

    /**
     * Логирование назначения пользователей на событие
     *
     * @param string $eventName
     * @param array|null $oldAssignedUserIds
     * @return void
     */
    public function logAssignedUsersActivity(string $eventName, ?array $oldEvent = null, ?array $oldAssignedUserIds = null): void
    {
        $this->load('users');
        $assignedUserIds = $this->users->pluck('id')->toArray();

        $assignedFor = null;
        if (count($assignedUserIds) > 1) {
            $assignedFor = 'всех в подчинении';
        } elseif (count($assignedUserIds) === 1) {
            $assignedFor = User::find($assignedUserIds[0])->name;
        }

        $properties = [
            'attributes' => [
                'title' => $this->title,
                'description' => $this->description,
                'start' => $this->start,
                'end' => $this->end,
                'assigned_for' => $assignedFor
            ]
        ];

        if ($oldAssignedUserIds) {
            $oldAssignedFor = [];

            if (count($oldAssignedUserIds) > 1) {
                $oldAssignedFor = 'всех в подчинении';
            } elseif (count($oldAssignedUserIds) === 1) {
                $oldAssignedFor = User::find($oldAssignedUserIds[0])->name;
            }

            $properties['old'] = [
                'title' => $oldEvent['title'],
                'description' => $oldEvent['description'],
                'start' => $oldEvent['start'],
                'end' => $oldEvent['end'],
                'assigned_for' => $oldAssignedFor
            ];
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($this)
            ->withProperties($properties)
            ->event($eventName)
            ->log($eventName);
    }


    /**
     * Логировать изменение статуса выполнения задачи
     *
     * @param bool $old
     * @param bool $new
     * @return void
     */
    public function logEventMark(bool $mark, string $title): void
    {
        activity()
            ->causedBy(Auth::user())
            ->performedOn($this)
            ->withProperties([
                'attributes' => [
                    'title' => $title,
                    'mark' => $mark
                ]
            ])
            ->event('event_mark')
            ->log('event_mark');
    }

    public function getIsDoneAttribute(): bool
    {
        return $this->pivot->is_done;
    }
}
