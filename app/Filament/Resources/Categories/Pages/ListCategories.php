<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Nueva Categoria')
                ->icon(Heroicon::PlusCircle),
        ];
    }

     public function table(Table $table): Table {
        return $table
        ->recordactions ([

            ViewAction::make()
                ->icon(Heroicon::Eye)
                ->color('info')
                ->label('Ver Categoria'),

            EditAction::make()
                ->icon(Heroicon::PencilSquare)
                ->color('warning')
                ->label('Editar Categoria'),



        ]);
    }
}
