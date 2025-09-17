<?php

namespace App\DataTables;

use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use App\DTO\DataTable\DatatableRequestDTO;
use App\Utilities\LogResolvers\LogResolver;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Repositories\Interfaces\ActivityLogs\ActivityLogRepositoryInterface;

class ActivityLogDataTable extends AbstractDataTable
{
    /**
     * Репозиторий для работы с данными
     *
     * @var ActivityLogRepositoryInterface
     */
    private ActivityLogRepositoryInterface $repository;

    public function __construct(ActivityLogRepositoryInterface $activityLogRepository)
    {
        $this->repository = $activityLogRepository;

        $this->setBuilder($this->repository->getQuery());
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function (Activity $log) {
                return $log->created_at->format('d.m.Y H:i');
            })
            ->editColumn('updated_at', function (Activity $log) {
                return $log->updated_at->format('d.m.Y H:i');
            })
            ->editColumn('description', function (Activity $log) {
                return LogResolver::resolveLogMessage($log);
            })
            ->addColumn('causer_name', function (Activity $log) {
                return $log->causer?->name ?? 'Система';
            })
            ->setRowId('id');
    }
}
