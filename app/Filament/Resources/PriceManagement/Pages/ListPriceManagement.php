<?php

namespace App\Filament\Resources\PriceManagement\Pages;

use App\Filament\Resources\PriceManagement\PriceManagementResource;
use Filament\Actions\CreateAction;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Resources\Pages\ListRecords;

class ListPriceManagement extends ListRecords
{
    protected static string $resource = PriceManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    public function table(Table $table): Table {
        return $table
        ->recordActions ([
                    EditAction::make('set_price')
                    ->label('Asignar Precio')
                    ->icon(Heroicon::CurrencyDollar)
                    ->color('success')
        ]);
    }
}
