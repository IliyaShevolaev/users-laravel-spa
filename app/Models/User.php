<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\User\Position;
use App\Enums\User\GenderEnum;
use App\Enums\User\StatusEnum;
use App\Models\User\Department;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Hash;
use App\Models\Scopes\ActiveUserScope;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Модель пользователя системы
 *
 * @property int $id
 * @property string $status статус активен/неактивен
 * @property string $password пароль
 * @method static User create(array<int|string, mixed> $attributes = [])
 * @method static User withoutScopeFind(int $id)
 * @method static Builder<User> where(mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @use HasFactory<UserFactory>
 */
#[ScopedBy([ActiveUserScope::class])]
class User extends Authenticatable
{
    //@phpstan-ignore-next-line
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

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
            set: fn (string|null $value) => $value === null ? $this->password : Hash::make($value),
        );
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
}
