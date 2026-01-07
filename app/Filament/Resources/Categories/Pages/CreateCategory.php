<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;



class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

     public function getFormActions(): array
    {
        return [

            $this->getCreateFormAction()
                ->label('Crear Categoría')
                ->icon(Heroicon::PlusCircle)
                ,
            $this->getCancelFormAction()
                ->color ('danger')
                ->label('Cancelar')
                ->icon(Heroicon::XCircle),

        ];
    }
}
