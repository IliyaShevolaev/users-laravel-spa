<?php

namespace App\Http\Controllers\Files;

use App\Http\Resources\Files\FileTemplateResource;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\DataTables\FileTemplatesDataTable;
use App\DTO\DataTable\DatatableRequestDTO;
use App\Services\Files\FileTemplateService;
use App\DTO\Files\Templates\GenerateFileDTO;
use App\DTO\Files\Templates\CreateFileTemplateDTO;
use App\Http\Requests\DataTables\DataTableRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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

    public function index(): AnonymousResourceCollection
    {
        $this->authorize('check-permission', 'fileTemplates-read');

        return FileTemplateResource::collection($this->repository->all());
    }

    public function datatable(DataTableRequest $dataTableRequest, FileTemplatesDataTable $datatble): JsonResponse
    {
        $this->authorize('check-permission', 'fileTemplates-read');

        $dto = DatatableRequestDTO::from($dataTableRequest->validated());

        return $datatble->json($dto);
    }

    public function store(CreateFileTemplateRequest $createFileTemplateRequest): void
    {
        $this->authorize('check-permission', 'fileTemplates-create');

        $dto = CreateFileTemplateDTO::from($createFileTemplateRequest->validated());

        $this->service->create($dto);
    }

    public function show(int $templateId): BinaryFileResponse
    {
        $this->authorize('check-permission', 'fileTemplates-read');

        $template = $this->repository->find($templateId);

        $path = $this->service->getTemplatePath($template->file_path);

        return response()->download($path);
    }

    public function edit(int $templateId)
    {
        $this->authorize('check-permission', 'fileTemplates-update');

        $template = $this->repository->find($templateId);

        return new FileTemplateResource($template);
    }

    public function update(CreateFileTemplateRequest $request, int $id): void
    {
        $this->authorize('check-permission', 'fileTemplates-update');

        $dto = CreateFileTemplateDTO::from($request->validated());

        $fileTemplate = $this->repository->find($id);

        $this->service->update($fileTemplate, $dto);
    }

    public function destroy(int $id): void
    {
        $this->authorize('check-permission', 'fileTemplates-delete');

        $fileTemplate = $this->repository->find($id);
        $this->service->delete($fileTemplate);
    }

    public function generateDocument(GenereteFileWithTemplateRequest $genereteFileWithTemplateRequest): void
    {
        $this->authorize('check-permission', 'fileTemplates-read');

        $dto = GenerateFileDTO::from($genereteFileWithTemplateRequest->validated());

        $this->service->generateDocument($dto);
    }
}
