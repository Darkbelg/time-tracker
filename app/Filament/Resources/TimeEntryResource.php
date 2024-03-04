<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeEntryResource\RelationManagers\ProjectsRelationManager;
use App\Models\Type;
use Filament\Forms;
use Filament\Tables;
use App\Models\Customer;
use Filament\Forms\Form;
use App\Models\TimeEntry;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TimeEntryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;

class TimeEntryResource extends Resource
{
    protected static ?string $model = TimeEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customers', 'name')
                            ->multiple()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->required(),
                    ]),
                Forms\Components\Select::make('project_customers')
                    ->relationship('project.customers', 'name',function (Builder $query, Forms\Get $get){
                        $projectId = $get('project_id');

                        return $query->whereHas('projects', function ($query) use ($projectId) {
                            $query->where('id', $projectId);
                        });
                    })
                    ->label('Customers')
                    ->selectablePlaceholder(false)
                    ->disabled(),
                Forms\Components\Select::make('type_id')
                    ->relationship('type', 'name')
                    ->live(),
                Forms\Components\DatePicker::make('date')
                    ->default(now())
                    ->maxDate(now())
                    ->displayFormat('d/m/Y')
                    ->required(),
                Forms\Components\TextInput::make('time')
                    ->numeric()
                    ->step(0.25)
                    ->minValue(0)
                    ->maxValue(9.75)
                    ->required(),
                Forms\Components\Textarea::make('comment')
                    ->requiredIf('type_id', fn() => Type::where('name', 'like', 'other')->value('id'))
                    ->validationMessages([
                        'required_if' => 'If other is selected a comment is required.',
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50, 100])
            ->columns([
                Tables\Columns\TextColumn::make('project.name')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.customers.name')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('type.name')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('time')
                    ->summarize(Tables\Columns\Summarizers\Sum::make())
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->relationship('type', 'name'),
                Filter::make('date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('date')
                            ->placeholder('YYYY-MM-DD')
                            ->displayFormat('Y-m-d')
                            ->maxDate(now()->format('Y-m-d')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!empty($data['date'])) {
                            return $query->whereDate('date', '=', $data['date']);
                        }

                        return $query;
                    })
                    ->indicateUsing(function (array $data): ?string {
                        // Customize the indicator for the active filter
                        if (!empty($data['date'])) {
                            return 'Day ' . Carbon::parse($data['date'])->toFormattedDateString();
                        }

                        return null;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimeEntries::route('/'),
            'create' => Pages\CreateTimeEntry::route('/create'),
            'edit' => Pages\EditTimeEntry::route('/{record}/edit'),
        ];
    }
}
