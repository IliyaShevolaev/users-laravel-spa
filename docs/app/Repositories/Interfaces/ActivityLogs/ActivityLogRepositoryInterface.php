<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces\ActivityLogs;

use App\Models\User\Position;
use Illuminate\Database\Eloquent\Builder;

interface ActivityLogRepositoryInterface
{
    /**
     * Возвращает query builder
     *
     * @return Builder<Position>
     */
    public function getQuery(): Builder;

}
