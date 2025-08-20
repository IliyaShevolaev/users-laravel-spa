<?php

namespace App\Models\Tasks;

use App\Policies\EventPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(EventPolicy::class)]
class Event extends Model
{
    protected $fillable = ['title', 'start', 'end', 'department_id', 'all_vision'];
}
