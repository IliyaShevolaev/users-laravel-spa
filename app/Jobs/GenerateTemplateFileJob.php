<?php

namespace App\Jobs;

use App\DTO\User\ExportUserDTO;
use App\Events\ReadyExportFileEvent;
use App\Models\Export\UserCityExport;
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
        private int $userId
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
            ->path("exports/" . $this->fileName);
        $templateProcessor->saveAs($outputPath);

        UserCityExport::create([
            'user_id' => $this->userId,
            'file_name' => $this->fileName,
            'is_user_downloaded' => false,
        ]);

        broadcast(new ReadyExportFileEvent($this->userId, $this->fileName));
    }
}
