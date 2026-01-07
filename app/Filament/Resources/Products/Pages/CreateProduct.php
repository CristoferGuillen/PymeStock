<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use Filament\Tables\Table;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;


 public function getFormActions(): array
    {
        return [

            $this->getCreateFormAction()
                ->label('Crear Producto')
                ->icon(Heroicon::PlusCircle)
                ,
            $this->getCancelFormAction()
                ->color ('danger')
                ->label('Cancelar')
                ->icon(Heroicon::XCircle),

        ];
    }
    
}


