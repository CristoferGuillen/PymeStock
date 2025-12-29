<?php

namespace App\Filament\Resources\Sales\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use App\Models\Product;

class SaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('customer_name')
                    ->required(),
                TextInput::make('customer_email')
                    ->email()
                    ->required(),
                DateTimePicker::make('sale_at')
                    -> required(),
                Select::make('prdocut_id')
                    ->label('Productos')
                    ->options(Product::query()->pluck('name', 'id'))
                    ->searchable()
                    ->multiple()
                    ->required(),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
