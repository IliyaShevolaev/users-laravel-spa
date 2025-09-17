<?php

declare(strict_types=1);

namespace App\DataTables;

use App\Models\Cities\City;
use App\Repositories\Interfaces\Cities\CityRepositoryInterface;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

/**
 * Таблиа городов
 */
class CitiesDataTable extends AbstractDataTable
{

    /**
     * Репозиторий для работы с данными
     *
     * @var CityRepositoryInterface
     */
    private CityRepositoryInterface $repository;

    public function __construct(CityRepositoryInterface $repository)
    {
        $this->repository = $repository;

        $this->setBuilder($this->repository->getQuery());
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<City> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function (City $city) {
                return $city->created_at->format('H:i d.m.Y ');
            })
            ->editColumn('updated_at', function (City $city) {
                return $city->updated_at->format('H:i d.m.Y');
            })
            ->setRowId('id');
    }
}
