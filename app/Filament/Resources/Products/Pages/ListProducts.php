<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
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
