<?php

namespace App\DataTables;

use App\Models\Roles\Role;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use App\DTO\DataTable\DatatableRequestDTO;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Repositories\Interfaces\Roles\RoleRepositoryInterface;

class RolesDataTable extends DataTable
{
    /**
     * Репозиторий для работы с данными
     *
     * @var RoleRepositoryInterface $repository
     */
    public function __construct(private RoleRepositoryInterface $repository)
    {
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Role> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function (Role $role) {
                return $role->created_at->format('d.m.Y H:i');
            })
            ->editColumn('updated_at', function (Role $role) {
                return $role->updated_at->format('d.m.Y H:i');
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Role>
     */
    public function query(DatatableRequestDTO $dto): QueryBuilder
    {
        $query = $this->repository->getQuery();

        if (isset($dto->sortBy) && isset($dto->sortOrder)) {
            $query->orderBy($dto->sortBy, $dto->sortOrder);
        }

        if (isset($dto->search)) {
            $query->where('name', 'like', '%' . $dto->search . '%');
        }

        return $query;
    }

    public function json(DatatableRequestDTO $dto): \Illuminate\Http\JsonResponse
    {
        $query = $this->query($dto);

        $totalRecords = $this->repository->count();
        $filteredRecords = $query->count();
        $paginateQuery = $query;

        if (isset($dto->page) && isset($dto->perPage)) {
            $perPage = $dto->perPage;
            $offset = ($dto->page - 1) * $perPage;
            $paginateQuery = $query->skip($offset)->take($perPage);
        }

        return response()->json([
            'data' => $this->dataTable($paginateQuery)->toJson(),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'draw' => $dto->draw ?? 0,
            'input' => $dto->all()
        ]);
    }
}
