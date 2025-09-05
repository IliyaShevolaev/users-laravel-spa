<?php

namespace App\Exports\Cities;

use App\Exports\Cities\Sheets\CitySheet;
use App\Exports\Cities\Sheets\RegionSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CitiesExport implements WithMultipleSheets
{
    /**
     * @return array
     */
    public function sheets(): array
    {
        return [new CitySheet(), new RegionSheet()];
    }
}
