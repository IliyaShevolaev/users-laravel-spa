<?php

namespace App\Jobs;

use App\Events\ReadyExportFileEvent;
use App\Models\User;
use App\Exports\Cities\CitiesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExportCitiesJob implements ShouldQueue
{
    use Queueable;

    public function __construct(private User $user)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fileName = "cities-by-{$this->user->name}-" . now()->timestamp . '.xlsx';
        $path = "exports/{$fileName}";

        Excel::store(new CitiesExport, $path, 'local');

        broadcast(new ReadyExportFileEvent($this->user->id, $fileName));
    }
}
