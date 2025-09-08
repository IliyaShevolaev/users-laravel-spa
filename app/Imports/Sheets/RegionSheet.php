<?php

declare(strict_types=1);

namespace App\Imports\Sheets;

use App\Models\Cities\Region;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class RegionSheet implements ToCollection, WithStartRow, WithChunkReading
{
    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        $regionsToInsert = $rows->filter()->map(function ($row) {
            return [
                'name' => $row[1],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });

        Region::insert($regionsToInsert->toArray());
    }

    public function chunkSize(): int
    {
        return 5000;
    }
}

