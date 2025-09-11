<?php

namespace App\Http\Controllers\Files;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\DataTables\FileTemplatesDataTable;
use App\DTO\DataTable\DatatableRequestDTO;
use App\Services\Files\FileTemplateService;
use App\DTO\Files\Templates\GenerateFileDTO;
use App\DTO\Files\Templates\CreateFileTemplateDTO;
use App\Http\Requests\DataTables\DataTableRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\Files\Templates\CreateFileTemplateRequest;
use App\Http\Requests\Files\Templates\GenereteFileWithTemplateRequest;
use App\Repositories\Interfaces\Files\FileTemplateRepositoryInterface;

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

    public function show(int $templateId): BinaryFileResponse
    {
        $template = $this->repository->find($templateId);

        $path = $this->service->getTemplatePath($template->file_path);

        return response()->download($path);
    }

    public function destroy(int $id): void
    {
        $this->authorize('check-permission', 'fileTemplates-delete');

        $fiteTemplate = $this->repository->find($id);
        $this->service->delete($fiteTemplate);
    }

    public function generateDocument(GenereteFileWithTemplateRequest $genereteFileWithTemplateRequest): void
    {
        $dto = GenerateFileDTO::from($genereteFileWithTemplateRequest->validated());

        $this->service->generateDocument($dto);
    }
}
