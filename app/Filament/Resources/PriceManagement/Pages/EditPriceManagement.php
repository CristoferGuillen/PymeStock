<?php

namespace App\Filament\Resources\PriceManagement\Pages;

use App\Filament\Resources\PriceManagement\PriceManagementResource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPriceManagement extends EditRecord
{
    protected static string $resource = PriceManagementResource::class;

    public function getHeading(): string
    {
        return 'Asignar Precio de Venta';
    }

    public function getSubheading(): ?string
    {
        return "Producto: {$this->record->name}";
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Precio asignado correctamente')
            ->body("El producto '{$this->record->name}' ahora está disponible en stock");
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['status'] = 'in_stock';
        
        unset($data['price_type']);
        unset($data['profit_percentage']);

        return $data;
    }
}
