<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Resources\Sales\SaleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        unset($data['items']);
        
        return $data;
    }

    protected function afterCreate(): void
    {

        
        $items = $this->data['items'] ?? [];
        
        foreach ($items as $item) {
            $this->record->saleItems()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
            ]);
        }
        
    }
}
