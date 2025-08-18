<?php

declare(strict_types=1);

namespace App\DataTables;

use App\DTO\DataTable\DatatableRequestDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
        $dataTable = (new EloquentDataTable($query))
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

        if (Auth::user()->getUserRolePermissionsCollection()->contains('roles-read')) {
            $dataTable->editColumn('roles', function (User $user) {
                return $user->roles->isNotEmpty()
                    ? $user->roles->first()->display_name
                    : trans('main.users.empty_role');
            });
        }

        return $dataTable;
    }

    public function json(DatatableRequestDTO $dto): \Illuminate\Http\JsonResponse
    {
        $query = $this->query($dto);

        $totalRecords = $this->repository->count();
        $filteredRecords = $query->count();
        $paginateQuery = $query;

        if (isset($dto->page) && isset($dto->perPage)) {
            $perPage = $dto->perPage;
            $offset = ($dto->page - 1) * $perPage;
            $paginateQuery = $query->skip($offset)->take($perPage);
        }

        return response()->json([
            'data' => $this->dataTable($paginateQuery)->toJson(),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'draw' => $dto->draw ?? 0,
            'input' => $dto->all()
        ]);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(DatatableRequestDTO $dto): QueryBuilder
    {
        $queryRelations = ['department', 'position'];

        $permissions = Auth::user()->getUserRolePermissionsCollection();
        if ($permissions->contains('roles-read')) {
            $queryRelations[] = 'roles';
        }

        $query = $this->repository->getQueryWithRelations($queryRelations);

        if (isset($dto->sortBy) && isset($dto->sortOrder)) {
            if ($dto->sortBy === 'roles' && $permissions->contains('roles-read')) {
                $query->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
                    ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                    ->select('users.*')
                    ->orderBy('roles.name', $dto->sortOrder);
            } else {
                $query->orderBy($dto->sortBy, $dto->sortOrder);
            }
        }

        if ($permissions->contains('users-find') && !empty($dto->search)) {
            $query->where('name', 'like', '%' . $dto->search . '%');
        }

        return $query;
    }
}
