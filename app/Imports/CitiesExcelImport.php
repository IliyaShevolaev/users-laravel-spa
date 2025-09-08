<?php

namespace App\Imports;

use App\Imports\Sheets\CitySheet;
use App\Imports\Sheets\RegionSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CitiesExcelImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new CitySheet(),
            1 => new RegionSheet()
        ];
    }
}
