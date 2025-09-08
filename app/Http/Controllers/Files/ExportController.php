<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Services\Export\ExportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function __construct(private ExportService $service)
    {
    }

    /**
     * Получить файл экспорта для скачивания по его названию

     * @param string $fileName
     * @return BinaryFileResponse
     */
    public function get(string $fileName): BinaryFileResponse
    {
        $path = $this->service->getFilePath($fileName);

        return response()->download($path);
    }

    /**
     * Отметить скачивание файла
     *
     * @param string $fileName
     * @return void
     */
    public function mark(string $fileName): void
    {
        $this->service->markFileAsDownloaded($fileName);
    }

    public function getFiles()
    {
        $files = $this->service->getUserMissDownloadedUserFiles();

        return response()->json($files);
    }
}
