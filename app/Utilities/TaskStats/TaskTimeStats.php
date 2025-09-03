<?php

declare(strict_types=1);

namespace App\Utilities\TaskStats;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\DTO\Tasks\Stats\StatsChartDTO;
use Illuminate\Database\Eloquent\Collection;

class TaskTimeStats
{
    /**
     * Вернуть статистику по времени событий по промежутку дат
     *
     * @param Collection $events
     * @param mixed $rangeStart
     * @param mixed $rangeEnd
     * @return StatsChartDTO
     */
    public static function handle(Collection|\Illuminate\Support\Collection $events, $rangeStart, $rangeEnd): StatsChartDTO
    {
        debugbar()->info($events);
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
            $label = $date->format($format);

            if ($format === 'd.m.Y') {
                $periodStart = $date->copy()->startOfDay();
                $periodEnd = $date->copy()->endOfDay();
            } elseif ($format === 'm.Y') {
                $periodStart = $date->copy()->startOfMonth();
                $periodEnd = $date->copy()->endOfMonth();
            } else {
                $periodStart = $date->copy()->startOfYear();
                $periodEnd = $date->copy()->endOfYear();
            }

            $hours = 0;

            foreach ($events as $event) {
                $eventStart = Carbon::parse($event->start);
                $eventEnd = Carbon::parse($event->endTime);

                if ($eventStart < $rangeStart)
                    $eventStart = $rangeStart->copy();
                if ($eventEnd > $rangeEnd)
                    $eventEnd = $rangeEnd->copy();

                $start = $eventStart->greaterThan($periodStart) ? $eventStart : $periodStart;
                $end = $eventEnd->lessThan($periodEnd) ? $eventEnd : $periodEnd;

                if ($start < $end) {
                    $hours += ceil($start->diffInHours($end));
                }
            }

            $categories[] = $label;
            $data[] = $hours;
        }

        return StatsChartDTO::from([
            'categories' => $categories,
            'data' => $data,
        ]);
    }
}
