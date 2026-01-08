<?php

namespace App\Filament\Resources\PriceManagement\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

class PriceManagementTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('unit_cost')
                    ->label('Precio de Compra')
                    ->money()
                    ->sortable(),

                TextColumn::make('units_available')
                    ->label('Unidades')
                    ->numeric()
                    ->alignCenter()
                    ->badge()
                    ->color('warning'),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn ($state) => 'Precio Pendiente'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
