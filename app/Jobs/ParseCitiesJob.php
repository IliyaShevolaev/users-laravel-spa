<?php

namespace App\Jobs;

use App\Imports\CitiesImport;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ParseCitiesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $apiUrl)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = Http::get($this->apiUrl);

        $tempFile = tempnam(sys_get_temp_dir(), 'cities') . '.csv';
        file_put_contents($tempFile, $response->body());

        Excel::import(new CitiesImport(), $tempFile);

        unlink($tempFile);
    }
}
