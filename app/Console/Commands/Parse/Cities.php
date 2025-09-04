<?php

namespace App\Console\Commands\Parse;

use App\Imports\CitiesImport;
use App\Jobs\ParseCitiesJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class Cities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:cities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse cities';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ParseCitiesJob::dispatch(config('services.parse_cities.api_url'));
    }
}
