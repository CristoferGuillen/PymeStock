<?php

namespace App\Filament\Resources\StockEntries\Schemas;

use App\Models\StockEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class StockEntryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Ingreso')
                    ->schema([
                        TextEntry::make('entry_number')
                            ->label('Número de Ingreso')
                            ->placeholder('-'),
                        TextEntry::make('entry_date')
                            ->label('Fecha de Ingreso')
                            ->date(),
                        TextEntry::make('notes')
                            ->label('Notas')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('user.name')
                            ->label('Recibido Por')
                            ->placeholder('-'),
                        TextEntry::make('items_count')
                            ->label('Items')
                            ->placeholder('-'),
                    
                    ])
                    ->columns(2),
                Section::make('Resumen')
                    ->schema([
                        TextEntry::make('total_cost')
                            ->label('Costo Total')
                            ->money(),
                    ])
                    ->collapsible(),    
                    ]);
               
    }
}
