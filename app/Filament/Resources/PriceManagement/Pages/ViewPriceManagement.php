<?php

namespace App\Filament\Resources\PriceManagement\Pages;

use App\Filament\Resources\PriceManagement\PriceManagementResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPriceManagement extends ViewRecord
{
    protected static string $resource = PriceManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
