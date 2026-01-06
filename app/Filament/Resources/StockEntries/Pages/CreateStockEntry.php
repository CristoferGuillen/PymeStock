<?php

namespace App\Filament\Resources\StockEntries\Pages;

use App\Filament\Resources\StockEntries\StockEntryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;


class CreateStockEntry extends CreateRecord
{
    protected static string $resource = StockEntryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $id = Auth::id();
        $data['user_id'] = $id;
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
