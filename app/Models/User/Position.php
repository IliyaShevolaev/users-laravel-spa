<?php

declare(strict_types=1);

namespace App\Models\User;

use App\Models\User;
use App\Policies\User\PositionPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

/**
 * Модель должности пользвателя
 *
 * @property int $id Id
 * @property string $name Название
 * @method static \Illuminate\Database\Eloquent\Builder<Position> create(array<string, mixed> $attributes = [])
 * @property-read Collection<int, User>|null $users Пользоватили с должностью
 */
class Position extends Model
{
    use SoftDeletes;

    /**
     * Автозаполняемые атрибуты
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Получить пользователей с этой должностью
     *
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'position_id', 'id');
    }
}
