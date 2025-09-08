<?php

declare(strict_types=1);

namespace App\Imports\Sheets;

use App\Models\Cities\City;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CitySheet implements ToCollection, WithStartRow, WithChunkReading
{
    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        $citiesToInsert = $rows->filter()->map(function ($row) {
            return [
                'name' => $row[1],
                'ip_start' => $row[3],
                'ip_end' => $row[4],
                'created_at' => now(),
                'region_id' => 1,
                'updated_at' => now(),
            ];
        });

        City::insert($citiesToInsert->toArray());
    }

    public function chunkSize(): int
    {
        return 5000;
    }
}

