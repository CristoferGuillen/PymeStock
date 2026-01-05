<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Resources\Sales\SaleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;

class ListSales extends ListRecords
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Nueva Venta')
                ->icon(Heroicon::PlusCircle),
        ];
    }

     public function table(Table $table): Table {
        return $table
        ->recordactions ([

            ViewAction::make()
                ->icon(Heroicon::Eye)
                ->color('info'),

            EditAction::make()
                ->icon(Heroicon::PencilSquare)
                ->color('warning'),



        ]);
    }
}
