<?php

declare(strict_types=1);

namespace App\DataTables;

use App\Models\Files\FileTemplate;
use App\Repositories\Interfaces\Files\FileTemplateRepositoryInterface;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

/**
 * Таблиа городов
 */
class FileTemplatesDataTable extends AbstractDataTable
{
    /**
     * Репозиторий для работы с данными
     *
     * @var FileTemplateRepositoryInterface
     */
    private FileTemplateRepositoryInterface $repository;

    public function __construct(FileTemplateRepositoryInterface $repository)
    {
        $this->repository = $repository;

        $this->setBuilder($this->repository->getQuery());
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<FileTemplate> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function (FileTemplate $file) {
                return $file->created_at->format('H:i d.m.Y');
            })
            ->editColumn('updated_at', function (FileTemplate $file) {
                return $file->updated_at->format('H:i d.m.Y');
            })
            ->setRowId('id');
    }
}
