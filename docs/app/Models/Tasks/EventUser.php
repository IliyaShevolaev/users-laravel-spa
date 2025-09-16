<?php

namespace App\Models\Tasks;

use App\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EventUser extends Pivot
{
    use LogsActivity;

    protected $fillable = ['user_id', 'event_id', 'is_done'];
    public $incrementing = true;

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->subject_id = $this->user_id;
        $activity->subject_type = User::class;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnly(['event.title'])
            ->logExcept(['user_id', 'event_id'])
            ->logOnlyDirty()
            ->useLogName('default');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
