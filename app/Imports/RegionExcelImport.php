<?php

namespace App\Imports;

use App\Imports\Sheets\RegionSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RegionExcelImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            1 => new RegionSheet(),
        ];
    }
}
