<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use Filament\Actions;
use Filament\Actions\ImportAction;
use App\Filament\Imports\ProjectImporter;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProjectResource;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportAction::make()
                ->importer(ProjectImporter::class)
                ->visible(auth()->user()->can('import_project'))
                ->color('primary')
        ];
    }
}
