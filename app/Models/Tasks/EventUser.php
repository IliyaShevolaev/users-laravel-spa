<?php

namespace App\Models\Tasks;

use Illuminate\Database\Eloquent\Model;

class EventUser extends Model
{
    protected $fillable = ['user_id', 'event_id', 'is_done'];
}
