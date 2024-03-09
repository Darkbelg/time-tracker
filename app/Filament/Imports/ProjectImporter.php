<?php

namespace App\Filament\Imports;

use App\Models\Customer;
use App\Models\Project;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ProjectImporter extends Importer
{
    protected static ?string $model = Project::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:50']),
            ImportColumn::make('customers')
                ->requiredMapping(),
        ];
    }

    public function resolveRecord(): ?Project
    {
        return Project::firstOrNew([
            'name' => $this->data['name'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your project import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }

    protected function afterSave(): void
    {
        $this->record
            ->customers()
            ->sync(
                collect(explode(',', $this->originalData["customers"]))
                    ->map(function ($item) {
                        return Customer::firstOrCreate(['name' => $item])->id;
                    })
            );
    }

    protected function beforeFill(): void
    {
        unset($this->data["customers"]);
    }
}
