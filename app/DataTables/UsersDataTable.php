<?php

declare(strict_types=1);

namespace App\DataTables;

use App\Models\User;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use App\Models\Scopes\ActiveUserScope;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Repositories\Interfaces\User\UserRepositoryInterface;

/**
 * Таблица пользователей
 */
class UsersDataTable extends DataTable
{

    /**
     * Репозиторий для работы с данными
     *
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->repository = $userRepository;
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<User> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('department_id', function ($user) {
                return $user->department->name ?? trans('main.users.without_department');
            })
            ->editColumn('position_id', function ($user) {
                return $user->position->name ?? trans('main.users.without_position');
            })
            ->editColumn('gender', function ($user) {
                return trans('main.users.genders.' . $user->gender->value);
            })
            ->editColumn('status', function ($user) {
                return trans('main.users.statuses.' . $user->status->value);
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('d.m.Y H:i');
            })
            ->editColumn('updated_at', function ($user) {
                return $user->updated_at->format('d.m.Y H:i');
            })
            ->addColumn('actions', function ($user) {
                return view('users.actions', compact('user'))->render();
            })
            ->rawColumns(['department_name', 'position_name', 'actions'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(): QueryBuilder
    {
        return User::query()->withoutGlobalScope(ActiveUserScope::class);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $this->builder()->language(['url' => 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/ru.json']);

        return $this->builder()
            ->setTableId('users-table')
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
                is_array(__('main.users.name')) ? '' : (string) Str::of(__('main.users.name'))->ucfirst()
            ),
            Column::make('email')->title(
                is_array(__('main.users.email')) ? '' : (string) Str::of(__('main.users.email'))->ucfirst()
            ),
            Column::make('department_id')->title(
                is_array(__('main.users.department')) ? '' : (string) Str::of(__('main.users.department'))->ucfirst()
            ),
            Column::make('position_id')->title(
                is_array(__('main.users.position')) ? '' : (string) Str::of(__('main.users.position'))->ucfirst()
            ),
            Column::make('gender')->title(
                is_array(__('main.users.gender')) ? '' : (string) Str::of(__('main.users.gender'))->ucfirst()
            ),
            Column::make('status')->title(
                is_array(__('main.users.status')) ? '' : (string) Str::of(__('main.users.status'))->ucfirst()
            ),
            Column::make('created_at')->title(
                is_array(__('main.users.created')) ? '' : (string) Str::of(__('main.users.created'))->ucfirst()
            ),
            Column::make('updated_at')->title(
                is_array(__('main.users.updated')) ? '' : (string) Str::of(__('main.users.updated'))->ucfirst()
            ),
            Column::computed('actions')
                ->title(
                    is_array(__('main.users.actions_buttons')) ?
                    '' :
                    (string) Str::of(__('main.users.actions_buttons'))->ucfirst()
                )
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }
}
