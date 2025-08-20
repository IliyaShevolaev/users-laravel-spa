<?php

namespace App\Models\Tasks;

use App\Policies\EventPolicy;
use App\Models\User\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

#[UsePolicy(EventPolicy::class)]
class Event extends Model
{
    protected $fillable = ['title', 'start', 'end', 'department_id', 'all_vision'];

    /**
     * Получить отдел соыбтия
     * 
     * @return HasOne<Department, $this>
     */
    public function department(): HasOne
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }
}
