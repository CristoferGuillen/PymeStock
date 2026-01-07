<?php

namespace App\Filament\Resources\Sales\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class SaleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos Del Cliente')
                    ->schema([
                        TextEntry::make('customer_name')
                            ->label('Nombre del Cliente'),
                        TextEntry::make('customer_email')
                            ->label('Correo Electrónico del Cliente'),
                    ]),

                Section::make('Detalles de la Venta')
                    ->schema([
                        TextEntry::make('sale_date')
                            ->dateTime()
                            ->label('Fecha de la Venta')
                            ->icon('heroicon-o-calendar'),
                        TextEntry::make('invoice_number')
                            ->placeholder('-')
                            ->label('Número de Factura'),
                        TextEntry::make('subtotal')
                            ->numeric()
                            ->label('Subtotal'),
                        TextEntry::make('total')
                            ->numeric()
                            ->label('Total'),
                    ]),

            ]);
    }
}
