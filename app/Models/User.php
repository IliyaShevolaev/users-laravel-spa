<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Tasks\Event;
use App\Models\User\Position;
use App\Enums\User\GenderEnum;
use App\Enums\User\StatusEnum;
use App\Models\User\Department;
use Illuminate\Support\Collection;
use Spatie\Activitylog\LogOptions;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Hash;
use App\Models\Scopes\ActiveUserScope;
use Laratrust\Contracts\LaratrustUser;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;
use Laratrust\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Модель пользователя системы
 *
 * @property int $id
 * @property string $password пароль
 * @property GenderEnum $gender пол
 * @property StatusEnum $status статус
 * @method static User create(array<int|string, mixed> $attributes = [])
 * @method static User withoutScopeFind(int $id)
 * @method static Builder<User> where(mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @use HasFactory<UserFactory>
 */
#[ScopedBy([ActiveUserScope::class])]
class User extends Authenticatable implements LaratrustUser
{
    //@phpstan-ignore-next-line
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasRolesAndPermissions;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'position_id',
        'gender',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gender' => GenderEnum::class,
            'status' => StatusEnum::class,
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Получить пользователя не применяя Scope
     * @param int $id
     * @return User
     */
    public static function withoutScopeFind(int $id): User
    {
        return static::query()->withoutGlobalScope(ActiveUserScope::class)->findOrFail($id);
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn(string|null $value) => $value === null ? $this->password : Hash::make($value),
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $properties = $activity->properties;

        $attributes = collect($properties->get('attributes', []));

        if ($attributes->has('department_id')) {
            $departmentId = $attributes->get('department_id');
            $departmentName = optional(Department::find($departmentId))->name;

            $attributes->put('department', $departmentName);
            $attributes->forget('department_id');
        }

        if ($attributes->has('position_id')) {
            $positionId = $attributes->get('position_id');
            $positionName = optional(Position::find($positionId))->name;

            $attributes->put('position', $positionName);
            $attributes->forget('position_id');
        }

        $properties->put('attributes', $attributes);

        $old = collect($properties->get('old', []));

        if ($old->has('department_id')) {
            $oldDepartmentId = $old->get('department_id');
            $oldDepartmentName = optional(Department::find($oldDepartmentId))->name;

            $old->put('department', $oldDepartmentName);
            $old->forget('department_id');
        }

        if ($old->has('position_id')) {
            $oldPositionId = $old->get('position_id');
            $oldPositionName = optional(Position::find($oldPositionId))->name;

            $old->put('position', $oldPositionName);
            $old->forget('position_id');
        }

        $properties->put('old', $old);

        $activity->properties = $properties;
    }

    /**
     * Получить отдел пользователя
     * @return HasOne<Department, $this>
     */
    public function department(): HasOne
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    /**
     * Получить должность пользователя
     * @return HasOne<Position, $this>
     */
    public function position(): HasOne
    {
        return $this->hasOne(Position::class, 'id', 'position_id');
    }

    /**
     * Получить задачи пользователя
     *
     * @return BelongsToMany<Event, User, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_user')->withPivot('is_done');
    }

    public function getUserRolePermissionsCollection(): Collection
    {
        if (!$this->relationLoaded('roles')) {
            $this->load([
                'roles' => function ($query) {
                    $query->withoutGlobalScopes();
                },
                'roles.permissions'
            ]);
        }

        if ($this->roles->isNotEmpty()) {
            $role = $this->roles->first();
            if ($role->permissions->isNotEmpty()) {
                return $role->permissions->pluck('name');
            }
        }

        return collect();
    }
}
