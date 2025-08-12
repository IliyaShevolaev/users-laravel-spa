<?php

namespace App\DataTables;

use App\Models\Roles\Role;
use App\Repositories\Interfaces\Roles\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

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
    public function query(): QueryBuilder
    {
        $query = $this->repository->getQuery();

        if ($this->request()->has('sort_by') && $this->request()->has('sort_order')) {
            $query->orderBy($this->request()->input('sort_by'), $this->request()->input('sort_order'));
        }

        if ($this->request()->has('search') && !empty($this->request()->input('search'))) {
            $query->where('name', 'like', '%' . $this->request()->input('search') . '%');
        }


        return $query;
    }

    public function ajax(): \Illuminate\Http\JsonResponse
    {
        $query = $this->query();

        $filteredRecords = $query->count();

        if ($this->request()->has('page') && $this->request()->has('per_page')) {
            $perPage = $this->request()->input('per_page');
            $offset = ($this->request()->input('page') - 1) * $perPage;
            $paginateQuery = $query->skip($offset)->take($perPage);
        }

        return response()->json([
            'data' => $this->dataTable($paginateQuery)->toJson(),
            'recordsFiltered' => $filteredRecords,
            'draw' => $this->request()->input('draw', 0),
            'input' => $this->request()->all()
        ]);
    }
}
