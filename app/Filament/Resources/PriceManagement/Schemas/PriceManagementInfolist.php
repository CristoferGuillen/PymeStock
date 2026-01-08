<?php

namespace App\Filament\Resources\PriceManagement\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class PriceManagementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de Precios')
                    ->columns(2)
                    ->schema([
                        TextInput::make('prev_unit_cost')
                            ->label('Costo Unitario Anterior')
                            ->numeric()
                            ->disabled()
                            ->columnSpan(1),
                        TextInput::make('unit_cost')
                            ->label('Precio Actual')
                            ->numeric()
                            ->disabled()
                            ->columnSpan(1),
                        TextInput::make('status')
                            ->label('Estado')
                            ->disabled()
                            ->columnSpan(2),
                    ]),
            ]);
    }
}
