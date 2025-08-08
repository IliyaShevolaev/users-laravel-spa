<?php

declare(strict_types=1);

namespace App\DataTables;

use Illuminate\Support\Str;
use App\Models\User\Position;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Repositories\Interfaces\User\Position\PositionRepositoryInterface;

/**
 * Таблица должностей пользователей
 */
class PositionsDataTable extends DataTable
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
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Position> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function ($position) {
                return $position->created_at->format('d.m.Y H:i');
            })
            ->editColumn('updated_at', function ($position) {
                return $position->updated_at->format('d.m.Y H:i');
            })
            ->setRowId('id');
    }

    public function ajax(): \Illuminate\Http\JsonResponse
    {
        $query = $this->query();

        $totalRecords = Position::count();

        $filteredRecords = $query->count();

        if ($this->request()->has('page') && $this->request()->has('per_page')) {
            $perPage = $this->request()->input('per_page');
            $offset = ($this->request()->input('page') - 1) * $perPage;
            $paginateQuery = $query->skip($offset)->take($perPage);
        }

        return response()->json([
            'data' => $this->dataTable($paginateQuery)->toJson(),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'draw' => $this->request()->input('draw', 0),
            'input' => $this->request()->all()
        ]);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Position>
     */
    public function query(): QueryBuilder
    {
        $query = Position::query();

        if ($this->request()->has('sort_by') && $this->request()->has('sort_order')) {
            $query->orderBy($this->request()->input('sort_by'), $this->request()->input('sort_order'));
        }

        if ($this->request()->has('search') && !empty($this->request()->input('search'))) {
            $query->where('name', 'like', '%' . $this->request()->input('search') . '%');
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $this->builder()->language(['url' => 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/ru.json']);

        return $this->builder()
            ->setTableId('positions-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle();
    }


    /**
     * Get the dataTable columns definition.
     *
     * @return Column[]
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title(
                is_array(__('main.title')) ? '' : (string) Str::of(__('main.title'))->ucfirst()
            ),
            Column::make('created_at')->title(
                is_array(__('main.users.created')) ? '' : (string) Str::of(__('main.users.created'))->ucfirst()
            ),
            Column::make('updated_at')->title(
                is_array(__('main.users.updated')) ? '' : (string) Str::of(__('main.users.updated'))->ucfirst()
            ),
        ];
    }
}
