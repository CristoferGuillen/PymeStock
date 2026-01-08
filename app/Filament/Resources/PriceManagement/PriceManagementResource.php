<?php

namespace App\Filament\Resources\PriceManagement;

use App\Filament\Resources\PriceManagement\Pages\CreatePriceManagement;
use App\Filament\Resources\PriceManagement\Pages\EditPriceManagement;
use App\Filament\Resources\PriceManagement\Pages\ListPriceManagement;
use App\Filament\Resources\PriceManagement\Pages\ViewPriceManagement;
use App\Filament\Resources\PriceManagement\Schemas\PriceManagementForm;
use App\Filament\Resources\PriceManagement\Schemas\PriceManagementInfolist;
use App\Filament\Resources\PriceManagement\Tables\PriceManagementTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PriceManagementResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    protected static ?string $recordTitleAttribute = 'Product';

    protected static ?string $navigationLabel = 'Gestión de Precios';

    protected static ?string $modelLabel = 'Precio Pendiente';

    protected static ?string $pluralModelLabel = 'Gestión de Precios';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return PriceManagementForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PriceManagementInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PriceManagementTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status', 'pending_pricing');
    }
    public static function getPages(): array
    {
        return [
            'index' => ListPriceManagement::route('/'),
            'create' => CreatePriceManagement::route('/create'),
            'view' => ViewPriceManagement::route('/{record}'),
            'edit' => EditPriceManagement::route('/{record}/edit'),
        ];
    }
}
