<?php

namespace App\DataTables;

use App\Models\Roles\Role;
use App\Models\User\UserDocument;
use App\Repositories\Interfaces\User\UserDocumentRepositoryInterface;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use App\DTO\DataTable\DatatableRequestDTO;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class UserDocumentsDataTable extends DataTable
{
    /**
     * Репозиторий для работы с данными
     *
     * @var UserDocumentRepositoryInterface $repository
     */
    public function __construct(private UserDocumentRepositoryInterface $repository)
    {
        
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<UserDocument> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function (UserDocument $document) {
                return $document->created_at->format('d.m.Y H:i');
            })
            ->editColumn('updated_at', function (UserDocument $document) {
                return $document->updated_at->format('d.m.Y H:i');
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<UserDocument>
     */
    public function query(DatatableRequestDTO $dto, int $userId): QueryBuilder
    {
        $query = $this->repository->getQuery();

        $query->where('user_id', $userId);

        if (isset($dto->sortBy) && isset($dto->sortOrder)) {
            $query->orderBy($dto->sortBy, $dto->sortOrder);
        }

        if (isset($dto->search)) {
            $query->where('name', 'ILIKE', '%' . $dto->search . '%');
        }

        return $query;
    }

    public function json(DatatableRequestDTO $dto, int $userId): \Illuminate\Http\JsonResponse
    {
        $query = $this->query($dto, $userId);

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
