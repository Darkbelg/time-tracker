<?php

namespace App\Filament\Resources\TimeEntryResource\Widgets;

use App\Models\TimeEntry;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TimeEntriesChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    public ?string $filter = 'this_week';

    protected static bool $isLazy = true;

    protected function getFilters(): ?array
    {
        return [
            'this_week' => 'This week',
            'last_week' => 'Last week',
            'this_month' => 'This month',
            'last_month' => 'Last month',
        ];
    }

    protected function getData(): array
    {
        $dataQuery = TimeEntry::query()
            ->select(DB::raw('SUM(time) as total_time'), 'date');

        $datesQuery = TimeEntry::query()
            ->select('date');


        if ($this->filter === 'this_week') {
            $dataQuery->where('date', '>=', now()->startOfWeek());
            $datesQuery->where('date', '>=', now()->startOfWeek());
        }

        if ($this->filter === 'last_week') {
            $dataQuery->where('date', '<', now()->startOfWeek())->where('date', '>=',  now()->subWeek(1)->startOfWeek());
            $datesQuery->where('date', '<', now()->startOfWeek())->where('date', '>=',  now()->subWeek(1)->startOfWeek());
        }

        if ($this->filter === 'this_month') {
            $dataQuery->where('date', '>=', now()->firstOfMonth());
            $datesQuery->where('date', '>=', now()->firstOfMonth());
        }

        if ($this->filter === 'last_month') {
            $dataQuery->where('date', '<', now()->firstOfMonth())->where('date', '>=', now()->subMonths(1)->firstOfMonth());
            $datesQuery->where('date', '<', now()->firstOfMonth())->where('date', '>=', now()->subMonths(1)->firstOfMonth());
        }

        $data = $dataQuery->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('total_time');

        $dates = $datesQuery->groupBy('date')
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
