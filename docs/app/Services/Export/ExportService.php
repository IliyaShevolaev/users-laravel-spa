<?php

declare(strict_types=1);

namespace App\Services\Export;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\Export\UserExport;
use Illuminate\Support\Facades\Storage;

class ExportService
{
    /**
     * Получить путь
     *
     * @param string $fileName
     * @return string
     */
    public function getFilePath(string $fileName): string
    {
        $path = storage_path("app/private/exports/{$fileName}");

        if (!file_exists($path)) {
            abort(404);
        }

        return $path;
    }

    /**
     * Отметить успешное получение файла пользователем
     *
     * @param string $fileName
     * @return void
     */
    public function markFileAsDownloaded(string $fileName): void
    {
        $userId = Auth::id();

        $downloadedMark = UserExport
            ::where('user_id', $userId)
            ->where('file_name', $fileName)
            ->firstOrFail();

        $downloadedMark->is_user_downloaded = true;
        $downloadedMark->save();

        $path = $this->getFilePath($fileName);

        unlink($path);
    }

    /**
     * Получить не скачанные пользователем файлы
     *
     * @return Collection
     */
    public function getUserMissDownloadedUserFiles(): Collection
    {
        $fileNames = UserExport
            ::where('user_id', Auth::id())
            ->where('is_user_downloaded', false)
            ->get(['file_name', 'file_type']);

        return $fileNames;
    }
}
