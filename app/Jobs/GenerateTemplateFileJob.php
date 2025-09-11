<?php

namespace App\Jobs;

use App\DTO\User\ExportUserDTO;
use App\Models\Export\UserExport;
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
        Log::info($fullFileName);

        Storage::disk('local')->makeDirectory('exports');

        $outputPath = Storage
            ::disk('local')
            ->path("exports/{$fullFileName}");
        $templateProcessor->saveAs($outputPath);

        UserExport::create([
            'user_id' => $this->userId,
            'file_name' => $fullFileName,
            'file_type' =>  $this->fileFormat,
            'is_user_downloaded' => false,
        ]);

        broadcast(new ReadyExportFileEvent($this->userId, $fullFileName));
    }
}
