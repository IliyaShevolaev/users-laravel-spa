<?php

namespace App\DataTables;

use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use App\DTO\DataTable\DatatableRequestDTO;
use App\Utilities\LogResolvers\LogResolver;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Repositories\Interfaces\ActivityLogs\ActivityLogRepositoryInterface;

class ActivityLogDataTable extends DataTable
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
            ->addColumn('subject_name', function (Activity $log) {
                return LogResolver::getSubjectName($log);
            })
            ->setRowId('id');
    }

    public function json(DatatableRequestDTO $dto, int $userId): \Illuminate\Http\JsonResponse
    {
        $query = $this->query($dto, $userId);

        $filteredRecords = $query->count();
        $paginateQuery = $query;

        if (isset($dto->page) && isset($dto->perPage)) {
            $perPage = $dto->perPage;
            $offset = ($dto->page - 1) * $perPage;
            $paginateQuery = $query->skip($offset)->take($perPage);
        }

        return response()->json([
            'data' => $this->dataTable($paginateQuery)->toJson(),
            'recordsFiltered' => $filteredRecords,
            'draw' => $dto->draw ?? 0,
            'input' => $dto->all()
        ]);
    }


    public function query(DatatableRequestDTO $dto, int $userId): QueryBuilder
    {
        $query = $this->repository->getQuery();

        $query->where('causer_id', $userId);

        if (isset($dto->sortBy) && isset($dto->sortOrder)) {
            $query->orderBy($dto->sortBy, $dto->sortOrder);
        }

        if (isset($dto->search)) {
            $query->where('name', 'like', '%' . $dto->search . '%');
        }

        return $query;
    }
}
