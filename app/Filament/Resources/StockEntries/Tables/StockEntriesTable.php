<?php

namespace App\Filament\Resources\StockEntries\Tables;


use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Table;

class StockEntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(self::columns())
            ->filters([])
            ->actions(self::actions())
            ->bulkActions(self::bulkActions())
            ->defaultSort('entry_date', 'desc');
    }

    private static function columns(): array
    {
        return [
            TextColumn::make('entry_number')
                ->label('N° Ingreso')
                ->searchable()
                ->sortable(),

            TextColumn::make('entry_date')
                ->label('Fecha')
                ->date('d/m/Y')
                ->sortable(),

            TextColumn::make('user.name')
                ->label('Usuario')
                ->searchable()
                ->sortable()
                ->default('N/A'),

            TextColumn::make('items_count')
                ->label('Items')
                ->counts('items')
                ->alignCenter(),

            TextColumn::make('total_cost')
                ->label('Costo Total')
                ->money('COP')
                ->sortable(),

            TextColumn::make('created_at')
                ->label('Creado')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    private static function actions(): array
    {
        return [
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make(),
        ];
    }

    private static function bulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }
}
