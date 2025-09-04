<?php

namespace App\Imports;

use App\Models\Cities\City;
use App\Models\Cities\Region;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CitiesImport implements ToCollection, WithChunkReading
{
    private Collection $regionsCache;

    public function __construct()
    {
        // Загружаем кеш всех существующих регионов один раз
        $this->regionsCache = Region::pluck('id', 'name');
    }

    /**
     * @param \Illuminate\Support\Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        $currentTime = now();

        $newRegionNames = $rows->pluck(4)
            ->filter()
            ->unique()
            ->reject(fn($name) => $this->regionsCache->has($name))
            ->values();

        if ($newRegionNames->isNotEmpty()) {
            $newRegionsData = $newRegionNames->map(fn($name) => [
                'name' => $name,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ])->toArray();

            Region::insert($newRegionsData);

            $newRegionsIds = Region::whereIn('name', $newRegionNames)->pluck('id', 'name');
            $this->regionsCache = $this->regionsCache->merge($newRegionsIds);
        }

        $citiesToInsert = $rows->map(function ($row) use ($currentTime) {
            $regionName = $row[4] ?? '';
            $cityName = $row[5] ?? '';

            if (empty($regionName) || empty($cityName)) {
                return null;
            }

            return [
                'name' => $cityName,
                'ip_start' => $row[0] ?? null,
                'ip_end' => $row[1] ?? null,
                'region_id' => $this->regionsCache->get($regionName) ?? null,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ];
        })->filter()->values()->all();

        if (!empty($citiesToInsert)) {
            City::insert($citiesToInsert);
        }
    }

    public function chunkSize(): int
    {
        return 5000;
    }
}
