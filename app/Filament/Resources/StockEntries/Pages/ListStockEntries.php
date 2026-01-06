<?php

namespace App\Filament\Resources\StockEntries\Pages;

use App\Filament\Resources\StockEntries\StockEntryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;


class ListStockEntries extends ListRecords
{
    protected static string $resource = StockEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Crear Ingreso')
            ->icon(Heroicon::PlusCircle),
        ];
    }  
    public function table(Table $table): Table {
        return $table
        ->recordactions ([

            ViewAction::make()
                ->icon(Heroicon::Eye)
                ->color('info')
                ->label('Ver '),

            EditAction::make()
                ->icon(Heroicon::PencilSquare)
                ->color('warning')
                ->label('Editar '),

            DeleteAction::make()
                ->icon(Heroicon::Trash)
                ->color('danger')
                ->label('Eliminar '),
        ]);
    }

    
}
