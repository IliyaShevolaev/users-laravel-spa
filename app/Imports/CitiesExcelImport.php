<?php

namespace App\Imports;

use App\Imports\Sheets\CitySheet;
use App\Imports\Sheets\RegionSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;

class CitiesExcelImport implements WithMultipleSheets
{
    use WithConditionalSheets;

    public function conditionalSheets(): array
    {
        return [
            0 => new CitySheet(),
            1 => new RegionSheet(),
        ];
    }
}
