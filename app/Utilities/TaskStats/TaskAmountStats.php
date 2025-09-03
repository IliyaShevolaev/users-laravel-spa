<?php

declare(strict_types=1);

namespace App\Utilities\TaskStats;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\DTO\Tasks\Stats\StatsChartDTO;
use Illuminate\Database\Eloquent\Collection;

class TaskAmountStats
{
    /**
     * Вернуть статистику по колличеству событий по промежутку дат
     *
     * @param Collection $events
     * @param mixed $rangeStart
     * @param mixed $rangeEnd
     * @return StatsChartDTO
     */
    public static function handle(Collection $events, $rangeStart, $rangeEnd): StatsChartDTO
    {
        if ($rangeStart->diffInDays($rangeEnd) >= 365) {
            $period = CarbonPeriod::create($rangeStart->startOfYear(), '1 year', $rangeEnd->endOfYear());
            $format = 'Y';
        } elseif ($rangeStart->diffInDays($rangeEnd) >= 31) {
            $period = CarbonPeriod::create($rangeStart->startOfMonth(), '1 month', $rangeEnd->endOfMonth());
            $format = 'm.Y';
        } else {
            $period = CarbonPeriod::create($rangeStart, '1 day', $rangeEnd);
            $format = 'd.m.Y';
        }

        $categories = [];
        $data = [];

        foreach ($period as $date) {
            if ($format === 'd.m.Y') {
                $label = $date->format($format);
                $count = $events
                    ->filter(
                        fn($e) =>
                        Carbon::parse($e->start)->startOfDay() <= $date && Carbon::parse($e->end)->endOfDay() >= $date
                    )
                    ->count();
            } elseif ($format === 'm.Y') {
                $periodStart = $date->copy()->startOfMonth();
                $periodEnd = $date->copy()->endOfMonth();
                $label = $date->format($format);

                $count = $events
                    ->filter(
                        fn($e) =>
                        Carbon::parse($e->start) <= $periodEnd && Carbon::parse($e->end) >= $periodStart
                    )
                    ->count();
            } else {
                $periodStart = $date->copy()->startOfYear();
                $periodEnd = $date->copy()->endOfYear();
                $label = $date->format($format);

                $count = $events
                    ->filter(
                        fn($e) =>
                        Carbon::parse($e->start) <= $periodEnd && Carbon::parse($e->end) >= $periodStart
                    )
                    ->count();
            }

            $categories[] = $label;
            $data[] = $count;
        }

        return StatsChartDTO::from([
            'categories' => $categories,
            'data' => $data,
        ]);
    }
}
