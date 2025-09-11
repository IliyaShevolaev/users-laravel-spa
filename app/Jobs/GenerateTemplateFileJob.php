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

        Storage::disk('local')->makeDirectory('exports');

        $outputPath = Storage
            ::disk('local')
            //->path("exports/" . $this->fileName . '.docx');
            ->path("exports/{$this->fileName}.{$this->fileFormat}");
        $templateProcessor->saveAs($outputPath);

        Log::info($this->fileFormat);

        UserExport::create([
            'user_id' => $this->userId,
            'file_name' => $this->fileName,
            'file_type' =>  $this->fileFormat,
            'is_user_downloaded' => false,
        ]);

        broadcast(new ReadyExportFileEvent($this->userId, "{$this->fileName}.{$this->fileFormat}"));
    }
}
