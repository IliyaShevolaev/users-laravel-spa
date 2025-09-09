<?php

declare(strict_types=1);

namespace App\Imports\Sheets;

use App\Models\Cities\City;
use Illuminate\Support\Str;
use App\Models\Cities\Region;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CitySheet implements ToCollection, WithHeadingRow, WithChunkReading
{
    private Collection $regions;

    public function __construct()
    {
        $this->regions = Region::all()->keyBy('name');
    }

    public function collection(Collection $rows)
    {
        $titleNames = [
            'name' => Str::slug(trans('main.cities.city')),
            'region' => Str::slug(trans('main.cities.region')),
            'ip_start' => Str::slug(trans('main.cities.ip_start')),
            'ip_end' => Str::slug(trans('main.cities.ip_end')),
        ];

        $citiesToInsert = $rows
            ->map(function ($row) use ($titleNames) {
                $regionName = $row->get($titleNames['region']);
                $region = $this->regions->get($regionName);

                $name = $row->get($titleNames['name']);
                $ipStart = $row->get($titleNames['ip_start']);
                $ipEnd = $row->get($titleNames['ip_end']);

                if (!$name || !$ipStart || !$ipEnd || !$region) {
                    return;
                }

                return [
                    'name' => $row->get($titleNames['name']),
                    'ip_start' => $row->get($titleNames['ip_start']),
                    'ip_end' => $row->get($titleNames['ip_end']),
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

