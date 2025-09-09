<?php

namespace App\Imports;

use App\Imports\Sheets\CitySheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CityExcelImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new CitySheet(),
        ];
    }
}
