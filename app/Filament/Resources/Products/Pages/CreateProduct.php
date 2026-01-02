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
                ->color('danger'),

            $this->getCreateAnotherFormAction()
                ->color ('gray'),
            $this->getCancelFormAction()
                ->color ('warning'),

        ];
    }
    
}


