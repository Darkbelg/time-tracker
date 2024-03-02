<?php

namespace App\Filament\Resources\TimeEntryResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use App\Exports\TimeEntryExport;
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
            Action::make('Export')
                ->action(fn (Collection $records) => dd($records))
            // ->action(fn () => Excel::download(new TimeEntryExport(), 'TimeEntry.xlsx')),
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
