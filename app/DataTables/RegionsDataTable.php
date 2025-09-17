<?php

declare(strict_types=1);

namespace App\DataTables;

use App\Models\Cities\Region;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use App\DTO\DataTable\DatatableRequestDTO;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Repositories\Interfaces\Cities\RegionRepositoryInterface;

/**
 * Таблиа отделов пользователей
 */
class RegionsDataTable extends AbstractDataTable
{

    /**
     * Репозиторий для работы с данными
     *
     * @var RegionRepositoryInterface
     */
    private RegionRepositoryInterface $repository;

    public function __construct(RegionRepositoryInterface $repository)
    {
        $this->repository = $repository;

        $this->setBuilder($this->repository->getQuery());
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Region> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function (Region $region) {
                return $region->created_at->format('H:i d.m.Y ');
            })
            ->editColumn('updated_at', function (Region $region) {
                return $region->updated_at->format('H:i d.m.Y');
            })
            ->setRowId('id');
    }
}
