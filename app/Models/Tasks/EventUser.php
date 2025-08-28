<?php

namespace App\Models\Tasks;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EventUser extends Pivot
{
    protected $fillable = ['user_id', 'event_id', 'is_done'];

}
