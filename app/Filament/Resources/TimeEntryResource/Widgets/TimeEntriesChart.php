<?php

namespace App\Filament\Resources\TimeEntryResource\Widgets;

use App\Models\TimeEntry;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TimeEntriesChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $data = TimeEntry::select(DB::raw('SUM(time) as total_time'), 'date')
            ->where('date', '>=', now()->startOfWeek())
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('total_time');

        $dates = TimeEntry::select('date')
            ->where('date', '>=', now()->startOfWeek())
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('y-m-d');
            });

        return [
            'datasets' => [
                [
                    'label' => 'Time entries',
                    'data' => $data,
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
