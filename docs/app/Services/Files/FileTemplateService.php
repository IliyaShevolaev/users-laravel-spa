<?php

declare(strict_types=1);

namespace App\Services\Files;

use App\DTO\User\ExportUserDTO;
use App\Models\Files\FileTemplate;
use Illuminate\Support\Facades\Auth;
use App\Jobs\GenerateTemplateFileJob;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Repositories\User\UserRepository;
use App\DTO\Files\Templates\GenerateFileDTO;
use App\DTO\Files\Templates\CreateFileTemplateDTO;
use App\Repositories\Interfaces\Files\FileTemplateRepositoryInterface;

class FileTemplateService
{
    public function __construct(
        private FileTemplateRepositoryInterface $repository,
        private UserRepository $userRepository,
    ) {
    }

    public function create(CreateFileTemplateDTO $dto): void
    {
        $filePath = $dto->fileTemplate->store('templates');

        $dto->setFilePath($filePath);
        FileTemplate::create($dto->all());
    }

    public function update(FileTemplate $fileTemplate, CreateFileTemplateDTO $dto): void
    {
        if ($dto->fileTemplate) {
            Storage::delete($fileTemplate->file_path);

            $filePath = $dto->fileTemplate->store('templates');
            $dto->setFilePath($filePath);
        }

        $fileTemplate->update($dto->all());
    }


    public function delete(FileTemplate $fileTemplate)
    {
        $pathToFile = $fileTemplate->file_path;

        $this->repository->delete($fileTemplate);

        Storage::delete($pathToFile);
    }

    public function generateDocument(GenerateFileDTO $generateFileDTO): void
    {
        $user = $this->userRepository->find($generateFileDTO->userId);
        $template = $this->repository->find($generateFileDTO->templateId);

        $exportDto = ExportUserDTO::from([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'gender' => trans('main.users.genders.' . $user->gender->value),
            'status' => trans('main.users.statuses.' . $user->status->value),
            'department' => $user->department?->name ?? '-',
            'position' => $user->position?->name ?? '-',
            'role' => $user->roleName,
            'created_at' => $user->created_at->format('H:i d.m.Y'),
            'updated_at' => $user->updated_at->format('H:i d.m.Y'),
        ]);

        $nowTimeStamps = now()->timestamp;

        GenerateTemplateFileJob::dispatch(
            $exportDto,
            $template->file_path,
            "{$template->name}-{$user->name}-{$nowTimeStamps}",
            Auth::id(),
            $generateFileDTO->format
        );
    }

    public function getTemplatePath(string $fileName): string
    {
        $path = storage_path("app/private/{$fileName}");

        if (!file_exists($path)) {
            abort(404);
        }

        return $path;
    }
}
