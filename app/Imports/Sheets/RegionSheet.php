<?php

declare(strict_types=1);

namespace App\Imports\Sheets;

use Illuminate\Support\Str;
use App\Models\Cities\Region;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class RegionSheet implements ToCollection, WithChunkReading, WithHeadingRow
{
    private Collection $regions;

    public function __construct()
    {
        $this->regions = Region::all()->keyBy('name');
    }

    public function collection(Collection $rows)
    {
        $regionNameRowTitle = Str::slug(trans('main.cities.region'));

        $regionsToInsert = $rows
            ->filter(function ($row) use ($regionNameRowTitle) {
                return $row->get($regionNameRowTitle) && !$this->regions->has($row->get($regionNameRowTitle));
            })
            ->map(function ($row) use ($regionNameRowTitle) {
                return [
                    'name' => $row->get($regionNameRowTitle),
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

