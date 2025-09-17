<?php

declare(strict_types=1);

namespace App\DataTables;

use App\Models\User\Department;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use App\DTO\DataTable\DatatableRequestDTO;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

abstract class AbstractDataTable extends DataTable
{
    protected QueryBuilder $builder;

    abstract public function dataTable(QueryBuilder $query): EloquentDataTable;

    protected function setBuilder(QueryBuilder $queryBuilder): void
    {
        $this->builder = $queryBuilder;
    }

    public function query(DatatableRequestDTO $dto, string $findField = 'name'): QueryBuilder
    {
        if (isset($dto->sortBy) && isset($dto->sortOrder)) {
            $this->builder->orderBy($dto->sortBy, $dto->sortOrder);
        }

        if (isset($dto->search)) {
            $this->builder->where($findField, 'like', '%' . $dto->search . '%');
        }

        return $this->builder;
    }

    public function json(DatatableRequestDTO $dto, string $findField = 'name'): \Illuminate\Http\JsonResponse
    {
        $query = $this->query($dto, $findField);

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
