<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;


class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles del Producto')
            ->schema([
                TextEntry::make('name')
                    ->label('Nombre del Producto'),
                TextEntry::make('description')
                    ->label('Descripción del Producto'),
                TextEntry::make('category_id')
                    ->numeric()
                    ->placeholder('-')
                    ->label('ID de Categoría'),
                TextEntry::make('unit_cost')
                    ->money()
                    ->label('Costo Unitario'),
                TextEntry::make('sales_price')
                    ->money()
                    ->label('Precio de Venta'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->label('Fecha de Creación'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->label('Fecha de Actualización'),
                     ]),

                Section::make('Inventario')
                    ->schema([
                        TextEntry::make('units_available')
                            ->numeric()
                            ->label('Unidades Disponibles'),
                        TextEntry::make('units_sold')
                            ->numeric()
                            ->label('Unidades Vendidas'),
                    ]),

            ]);
    }
}
