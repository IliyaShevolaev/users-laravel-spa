<?php

namespace App\Jobs;

use App\Imports\CitiesExcelImport;
use App\Imports\CityExcelImport;
use App\Imports\RegionExcelImport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportCitiesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $filePath)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Excel::import(new RegionExcelImport(), $this->filePath, 'local');
        Excel::import(new CityExcelImport(), $this->filePath, 'local');

        Storage::disk('local')->delete($this->filePath);
    }
}
