<?php

declare(strict_types=1);

namespace App\Exports\Cities\Sheets;

use App\Models\Cities\City;
use App\Models\Cities\Region;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class RegionSheet implements FromCollection, WithMapping, WithHeadings, WithTitle
{
    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return Region::orderBy('id', 'asc')->get();
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'ID',
            trans('main.cities.region'),
            trans('main.cities.created'),
            trans('main.cities.updated'),
        ];
    }

    public function map($region): array
    {
        return [
            $region->id,
            $region->name,
            $region->created_at->format('H:i d.m.Y'),
            $region->updated_at->format('H:i d.m.Y'),
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return trans('main.cities.regions_sheet');
    }
}
