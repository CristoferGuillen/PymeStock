<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),

                ImageColumn::make('attachment')
                    ->label('Imagen')
                    ->imageHeight(50)
                    ->circular(),

                TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('units_available')
                    ->label('Unidades')
                    ->sortable(),

                TextColumn::make('unit_cost')
                    ->label('Costo por unidad')
                    ->money()
                    ->sortable(),

                TextColumn::make('sales_price')
                    ->label('Precio de venta')
                    ->money()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->sortable()
                    ->badge()
                    ->formatStateUsing(fn (mixed $state): string => self::statusLabel($state))
                    ->color(fn (mixed $state): string => self::statusColor($state)),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
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

    private static function statusLabel(mixed $state): string
    {
        return match (self::normalizeStatus($state)) {
            'pending_pricing' => 'Pendiente de precio',
            'in_stock' => 'En stock',
            'out_of_stock' => 'Sin stock',

            'active', 'activo', 'available', 'disponible', 'enabled', 'habilitado', '1', 'true' => 'Activo',
            'inactive', 'inactivo', 'unavailable', 'no disponible', 'disabled', 'deshabilitado', '0', 'false' => 'Inactivo',

            default => filled($state) ? ucfirst(str_replace('_', ' ', (string) $state)) : 'Sin estado',
        };
    }

    private static function statusColor(mixed $state): string
    {
        return match (self::normalizeStatus($state)) {
            'pending_pricing' => 'warning',
            'in_stock' => 'success',
            'out_of_stock' => 'danger',

            'active', 'activo', 'available', 'disponible', 'enabled', 'habilitado', '1', 'true' => 'success',
            'inactive', 'inactivo', 'unavailable', 'no disponible', 'disabled', 'deshabilitado', '0', 'false' => 'danger',

            default => 'gray',
        };
    }

    private static function normalizeStatus(mixed $state): string
    {
        return mb_strtolower(trim((string) $state));
    }
}