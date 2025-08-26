<?php

declare(strict_types=1);

namespace App\Repositories\ActivityLogs;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Interfaces\ActivityLogs\ActivityLogRepositoryInterface;

class ActivityLogRepository implements ActivityLogRepositoryInterface
{

    public function getQuery(): Builder
    {
        return Activity::query();
    }
}
