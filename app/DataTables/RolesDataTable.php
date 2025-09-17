<?php

namespace App\DataTables;

use App\Models\Roles\Role;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use App\DTO\DataTable\DatatableRequestDTO;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Repositories\Interfaces\Roles\RoleRepositoryInterface;

class RolesDataTable extends AbstractDataTable
{
    /**
     * Репозиторий для работы с данными
     *
     * @var RoleRepositoryInterface $repository
     */
    public function __construct(private RoleRepositoryInterface $repository)
    {
        $this->setBuilder($this->repository->getQuery());
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

    public function json(DatatableRequestDTO $dto, string $findField = 'name'): \Illuminate\Http\JsonResponse
    {
        return $this->json($dto, $findField);
    }
}
