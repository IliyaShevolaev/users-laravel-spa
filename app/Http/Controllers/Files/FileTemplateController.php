<?php

namespace App\Http\Controllers\Files;

use App\DTO\Files\Templates\CreateFileTemplateDTO;
use App\Http\Requests\Files\Templates\CreateFileTemplateRequest;
use App\Repositories\Interfaces\Files\FileTemplateRepositoryInterface;
use App\Services\Files\FileTemplateService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\DataTables\FileTemplatesDataTable;
use App\DTO\DataTable\DatatableRequestDTO;
use App\Http\Requests\DataTables\DataTableRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FileTemplateController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private FileTemplateService $service,
        private FileTemplateRepositoryInterface $repository
    ) {
    }

    public function datatable(DataTableRequest $dataTableRequest, FileTemplatesDataTable $datatble): JsonResponse
    {
        $this->authorize('check-permission', 'fileTemplates-read');

        $dto = DatatableRequestDTO::from($dataTableRequest->validated());

        return $datatble->json($dto);
    }

    public function store(CreateFileTemplateRequest $createFileTemplateRequest): void
    {
        $dto = CreateFileTemplateDTO::from($createFileTemplateRequest->validated());

        $this->service->create($dto);
    }
}
