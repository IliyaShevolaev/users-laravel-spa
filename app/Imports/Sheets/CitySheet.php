<?php

declare(strict_types=1);

namespace App\Imports\Sheets;

use App\Models\Cities\City;
use App\Models\Cities\Region;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CitySheet implements ToCollection, WithStartRow, WithChunkReading
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
        $citiesToInsert = $rows
            ->map(function ($row) {
                $regionName = $row->get(2);
                $region = $this->regions->get($regionName);

                if (!$region) {
                    return null;
                }

                return [
                    'name' => $row->get(1),
                    'ip_start' => $row->get(3),
                    'ip_end' => $row->get(4),
                    'region_id' => $region->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->filter()
            ->unique(function ($item) {
                return $item['ip_start'] . '-' . $item['ip_end'];
            });

        $citiesToInsert->chunk(1000)->each(function ($chunk) {
            
            City::upsert(
                $chunk->toArray(),
                ['ip_start', 'ip_end'],
                ['name', 'region_id', 'created_at', 'updated_at']
            );
        });
    }


    public function chunkSize(): int
    {
        return 5000;
    }
}

