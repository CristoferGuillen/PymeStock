<?php

namespace App\Filament\Resources\PriceManagement\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class PriceManagementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Información del Producto')
                    ->description('Datos del producto pendiente de valoración')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre del Producto')
                            ->disabled(),

                        TextInput::make('category.name')
                            ->label('Categoría')
                            ->disabled(),

                        TextInput::make('unit_cost')
                            ->label('Precio de Compra por Unidad')
                            ->prefix('$')
                            ->disabled(),

                        TextInput::make('units_available')
                            ->label('Unidades Disponibles')
                            ->disabled(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Configuración de Precio de Venta')
                    ->description('Define el precio de venta del producto')
                    ->schema([
                        Toggle::make('price_type')
                            ->label('Cálculo automático de precio')
                            ->helperText('Activar para calcular el precio de venta automáticamente con un porcentaje')
                            ->inline(false)
                            ->onIcon('heroicon-m-calculator')
                            ->offIcon('heroicon-m-pencil-square')
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if (!$state) {
                                    $set('profit_percentage', null);
                                }
                            }),

                        TextInput::make('profit_percentage')
                            ->label('Porcentaje de ganancia (%)')
                            ->helperText('Ingresa el porcentaje de ganancia sobre el costo')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(1000)
                            ->live(onBlur: true)
                            ->visible(fn (Get $get): bool => $get('price_type') === true)
                            ->required(fn (Get $get): bool => $get('price_type') === true)
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $percentage = floatval($state ?? 0);
                                $purchasePrice = floatval($get('unit_cost') ?? 0);

                                if ($purchasePrice > 0 && $percentage >= 0) {
                                    $salePrice = $purchasePrice * (1 + ($percentage / 100));
                                    $set('sales_price', round($salePrice, 2));
                                }
                            }),

                        TextInput::make('sales_price')
                            ->label('Precio de venta')
                            ->helperText(fn (Get $get): string => 
                                $get('price_type') === true 
                                    ? 'Calculado automáticamente según el porcentaje' 
                                    : 'Ingresa el precio de venta manualmente'
                            )
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0.01)
                            ->disabled(fn (Get $get): bool => $get('price_type') === true)
                            ->dehydrated(),
                    ])
                    ->columns(1),
            ]);
    }
}

