<?php

declare(strict_types=1);

namespace App\DataTables;

use App\Models\User\Position;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use App\DTO\DataTable\DatatableRequestDTO;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Repositories\Interfaces\User\Position\PositionRepositoryInterface;

/**
 * Таблица должностей пользователей
 */
class PositionsDataTable extends AbstractDataTable
{
    /**
     * Репозиторий для работы с данными
     *
     * @var PositionRepositoryInterface
     */
    private PositionRepositoryInterface $repository;

    public function __construct(PositionRepositoryInterface $positionRepository)
    {
        $this->repository = $positionRepository;

        $this->setBuilder($this->repository->getQuery());
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Position> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function (Position $position) {
                return $position->created_at->format('d.m.Y H:i');
            })
            ->editColumn('updated_at', function (Position $position) {
                return $position->updated_at->format('d.m.Y H:i');
            })
            ->setRowId('id');
    }
}
