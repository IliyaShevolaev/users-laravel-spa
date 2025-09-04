<?php

declare(strict_types=1);

namespace App\DataTables;

use App\Models\Cities\City;
use App\Repositories\Interfaces\Cities\CityRepositoryInterface;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use App\DTO\DataTable\DatatableRequestDTO;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

/**
 * Таблиа городов
 */
class CitiesDataTable extends DataTable
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
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<City> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('name', function (City $city) {
                return $city->name;
            })
            ->editColumn('created_at', function (City $city) {
                return $city->created_at->format('H:i d.m.Y ');
            })
            ->editColumn('updated_at', function (City $city) {
                return $city->updated_at->format('H:i d.m.Y');
            })
            ->setRowId('id');
    }


    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<City>
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
}
