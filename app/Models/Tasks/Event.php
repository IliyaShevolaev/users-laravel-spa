<?php

namespace App\Models\Tasks;

use App\Models\User;
use App\Policies\EventPolicy;
use App\Models\User\Department;
use Illuminate\Database\Eloquent\Model;
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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_users')->withPivot('is_done');
    }
}
