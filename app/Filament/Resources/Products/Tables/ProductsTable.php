<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Producto'),
                ImageColumn::make('attachment')
                    ->label('imagen')
                    ->imageHeight(50)
                    ->circular(),
                TextColumn::make('description')
                    ->searchable()
                    ->label('Descripción'),
                TextColumn::make('category_id')
                    ->numeric()
                    ->sortable()
                    ->label('Categoria'),
                TextColumn::make('units_available')
                    ->label('Unidades')
                    ->sortable(),
                TextColumn::make('unit_cost')
                    ->money()
                    ->sortable()
                    ->label('Costo por unidad'),
                TextColumn::make('sales_price')
                    ->money()
                    ->sortable()
                    ->label('Precio de venta'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
