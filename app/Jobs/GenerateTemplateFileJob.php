<?php

namespace App\Jobs;

use App\DTO\User\ExportUserDTO;
use App\Models\Export\UserExport;
use App\Models\User\UserDocument;
use Illuminate\Support\Facades\Log;
use App\Events\ReadyExportFileEvent;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateTemplateFileJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private ExportUserDTO $exportUserDTO,
        private string $templatePath,
        private string $fileName,
        private int $userId,
        private string $fileFormat
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $templateProcessor = new TemplateProcessor(storage_path('app/private/' . $this->templatePath));
        $templateProcessor->setValues($this->exportUserDTO->all());

        $fullFileName = "{$this->fileName}.{$this->fileFormat}";

        Storage::disk('local')->makeDirectory('exports');

        $exportPath = Storage
            ::disk('local')
            ->path("exports/{$fullFileName}");
        $templateProcessor->saveAs($exportPath);

        Storage::disk('local')->makeDirectory('documents');

        Storage::disk('local')->copy("exports/{$fullFileName}", "documents/{$fullFileName}");

        UserExport::create([
            'user_id' => $this->userId,
            'file_name' => $fullFileName,
            'file_type' => $this->fileFormat,
            'is_user_downloaded' => false,
        ]);

        UserDocument::create([
            'user_id' => $this->exportUserDTO->id,
            'file_name' => $fullFileName,
        ]);

        broadcast(new ReadyExportFileEvent($this->userId, $fullFileName));
    }
}
