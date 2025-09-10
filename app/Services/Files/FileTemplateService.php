<?php

declare(strict_types=1);

namespace App\Services\Files;

use App\Models\Files\FileTemplate;
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

    public function delete(FileTemplate $fileTemplate)
    {
        $pathToFile = $fileTemplate->file_path;

        $this->repository->delete($fileTemplate);

        Storage::delete($pathToFile);
    }

    public function generateDocument(GenerateFileDTO $generateFileDTO)
    {
        $user = $this->userRepository->find($generateFileDTO->userId);
        $template = $this->repository->find($generateFileDTO->templateId);


        $templateProcessor = new TemplateProcessor(storage_path('app/private/' . $template->file_path));

        $values = [];

        $values['name'] = $user->name;
        $values['email'] = $user->email;
        $values['gender'] = trans('main.users.genders.' . $user->gender->value);
        $values['status'] = trans('main.users.statuses.' . $user->status->value);
        $values['department'] = $user->department?->name ?? '-';
        $values['position'] = $user->position?->name ?? '-';
        $values['role'] = $user->roleName;
        $values['created_at'] = $user->created_at->format('H:i d.m.Y');
        $values['updated_at'] = $user->updated_at->format('H:i d.m.Y');

        debugbar()->info($values);

        $templateProcessor->setValues($values);

        Storage::disk('local')->makeDirectory('generated');

        $outputPath = Storage
            ::disk('local')
            ->path("generated/{$template->name}-{$user->name}-" . now()->timestamp . $generateFileDTO->format);
        $templateProcessor->saveAs($outputPath);

        return $outputPath;
    }
}
