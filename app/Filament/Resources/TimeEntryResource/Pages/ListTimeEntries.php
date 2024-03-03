<?php

namespace App\Filament\Resources\TimeEntryResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use App\Exports\TimeEntryExport;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\IconPosition;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TimeEntryResource;
use  Illuminate\Database\Eloquent\Collection;

class ListTimeEntries extends ListRecords
{
    protected static string $resource = TimeEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ActionGroup::make([
                Action::make('This month')
                    ->action(function () {
                        $user = auth()->user();
                        $date = now();
                        $currentYear = $date->format('y');
                        $month = $date->format('m');
                        return Excel::download(new TimeEntryExport($date), "Timesheet_{$user->last_name}_{$user->first_name}_{$currentYear}_{$month}.xlsx");
                    }),
                Action::make('Last month')
                    ->action(function () {
                        $user = auth()->user();
                        $date = now()->subMonth();
                        $currentYear = $date->format('y');
                        $month = $date->format('m');
                        return Excel::download(new TimeEntryExport($date), "Timesheet_{$user->last_name}_{$user->first_name}_{$currentYear}_{$month}.xlsx");
                    }),
            ])
            ->label('Export')
            ->button()
            ->icon('heroicon-m-arrow-down-circle')
            ->iconPosition(IconPosition::Before),

        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make(),
            'This Week' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('date', '>=', now()->startOfWeek())),
            'Last Week' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('date', '<', now()->startOfWeek())->where('date', '>=',  now()->subWeek(1)->startOfWeek())),
            'This Month' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('date', '>=', now()->firstOfMonth())),
            'Last Month' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('date', '<', now()->firstOfMonth())->where('date', '>=', now()->subMonths(1)->firstOfMonth())),
        ];
    }
}
