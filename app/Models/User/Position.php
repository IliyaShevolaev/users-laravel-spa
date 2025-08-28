<?php

declare(strict_types=1);

namespace App\Models\User;

use App\Models\User;
use Spatie\Activitylog\LogOptions;
use App\Policies\User\PositionPolicy;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
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
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->useLogName($this->name);
    }
}
