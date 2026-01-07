<?php

namespace App\Filament\Resources\Sales\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Sales\SaleResource;
use Filament\Support\Icons\Heroicon;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;


     public function getFormActions(): array
    {
        return [

            $this->getCreateFormAction()
                ->label('Crear Venta')
                ->icon(Heroicon::PlusCircle)
                ,
            $this->getCancelFormAction()
                ->color ('danger')
                ->label('Cancelar')
                ->icon(Heroicon::XCircle),

        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->cachedItems = $data['items'] ?? [];
        unset($data['items']);
        
        return $data;
    }

    protected function afterCreate(): void
    {
        $items = $this->cachedItems ?? [];
        
        foreach ($items as $item) {
            $this->record->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'], 
                'subtotal' => $item['subtotal'] ?? ($item['quantity'] * $item['unit_price']),
            ]);
        }

       
        $this->record->refresh();
        $this->record->updateTotals();
    }

    private $cachedItems = [];
}
