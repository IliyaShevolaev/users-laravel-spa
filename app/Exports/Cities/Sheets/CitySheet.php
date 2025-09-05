<?php

declare(strict_types=1);

namespace App\Exports\Cities\Sheets;

use App\Models\Cities\City;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CitySheet implements FromCollection, WithMapping, WithHeadings, WithTitle, WithEvents
{
    use RegistersEventListeners;

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return City::with(['region'])->orderBy('id', 'asc')->get();
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'ID',
            trans('main.cities.city'),
            trans('main.cities.region'),
            trans('main.cities.ip_start'),
            trans('main.cities.ip_end'),
            trans('main.cities.created'),
            trans('main.cities.updated'),
        ];
    }

    public function map($city): array
    {
        return [
            $city->id,
            $city->name,
            $city->region->name,
            $city->ip_start,
            $city->ip_end,
            $city->created_at->format('H:i d.m.Y'),
            $city->updated_at->format('H:i d.m.Y'),
        ];
    }

    public static function afterSheet(AfterSheet $event)
    {
        $cities = City::with('region')->orderBy('id', 'asc')->get();

        $row = 2;
        foreach ($cities as $city) {
            $cell = 'C' . $row;

            $regionRow = $city->region->id + 1;
            $event
                ->sheet
                ->getCell($cell)
                ->getHyperlink()
                ->setUrl("sheet://'" . trans('main.cities.regions_sheet') . "'!A{$regionRow}");

            $row++;
        }
    }

    public function title(): string
    {
        return trans('main.cities.cities_sheet');
    }
}
