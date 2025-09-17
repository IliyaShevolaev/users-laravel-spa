<?php

declare(strict_types=1);

namespace App\DataTables;

use App\Models\User\Department;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Repositories\Interfaces\User\Department\DepartmentRepositoryInterface;

/**
 * Таблиа отделов пользователей
 */
class DepartmentsDataTable extends AbstractDataTable
{
    /**
     * Репозиторий для работы с данными
     *
     * @var DepartmentRepositoryInterface
     */
    private DepartmentRepositoryInterface $repository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->repository = $departmentRepository;

        $this->setBuilder($this->repository->getQuery());
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Department> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function (Department $department) {
                return $department->created_at->format('H:i d.m.Y ');
            })
            ->editColumn('updated_at', function (Department $department) {
                return $department->updated_at->format('H:i d.m.Y');
            })
            ->setRowId('id');
    }
}
