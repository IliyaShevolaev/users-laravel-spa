<?php

namespace App\Imports;

use App\Models\Cities\City;
use App\Models\Cities\Region;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CitiesImport implements ToCollection, WithChunkReading
{
    
    /**
     * @param \Illuminate\Support\Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        $regions = Region::pluck('id', 'name');
        $currentTime = now();

        $citiesToInsert = $rows->map(function ($row) use (&$regions, $currentTime) {
            $regionName = $row[4] ?? null;
            $cityName = $row[5] ?? null;

            if (empty($regionName) || empty($cityName)) {
                return null;
            }

            if (!$regions->has($regionName)) {
                $region = Region::create(['name' => $regionName]);
                $regions->put($regionName, $region->id);
            }

            $regionId = $regions->get($regionName);

            return [
                'name' => $cityName,
                'ip_start' => $row[0],
                'ip_end' => $row[1],
                'region_id' => $regionId,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ];
        })->filter();

        City::insert($citiesToInsert->toArray());
    }

    public function chunkSize(): int
    {
        return 5000;
    }
}
