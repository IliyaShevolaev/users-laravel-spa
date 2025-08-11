<?php

declare(strict_types=1);

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
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
            ->editColumn('department_id', function (User $user) {
                return $user->department->name ?? trans('main.users.without_department');
            })
            ->editColumn('position_id', function (User $user) {
                return $user->position->name ?? trans('main.users.without_position');
            })
            ->editColumn('gender', function (User $user) {
                return trans('main.users.genders.' . $user->gender->value);
            })
            ->editColumn('status', function (User $user) {
                return trans('main.users.statuses.' . $user->status->value);
            })
            ->editColumn('created_at', function (User $user) {
                return $user->created_at->format('H:i d.m.Y');
            })
            ->editColumn('updated_at', function (User $user) {
                return $user->updated_at->format('H:i d.m.Y');
            });
    }

    public function ajax(): \Illuminate\Http\JsonResponse
    {
        $query = $this->query();

        $totalRecords = $this->repository->count();

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
     * @return QueryBuilder<User>
     */
    public function query(): QueryBuilder
    {
        $query = $this->repository->getQueryWithRelations();

        if ($this->request()->has('sort_by') && $this->request()->has('sort_order')) {
            $query->orderBy($this->request()->input('sort_by'), $this->request()->input('sort_order'));
        }

        if ($this->request()->has('search') && !empty($this->request()->input('search'))) {
            $query->where('name', 'like', '%' . $this->request()->input('search') . '%');
        }

        return $query;
    }
}
