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
    private Collection $regions;

    public function __construct()
    {
        $this->regions = Region::all()->keyBy('name');
    }

    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        $regionsToInsert = $rows
            ->filter(function ($row) {
                return !$this->regions->has($row->get(1));
            })
            ->map(function ($row) {
                return [
                    'name' => $row->get(1),
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

