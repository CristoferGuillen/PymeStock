<?php
namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Models\Category;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                    
                TextInput::make('description')
                    ->label('Descripcion')
                    ->required(),
                    
                Select::make('category_id')
                    ->label('Categoria')
                    ->options(Category::query()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                    
                TextInput::make('units_available')
                    ->label('Unidades disponibles')
                    ->numeric()
                    ->required()
                    ->default(0),
                    
                TextInput::make('unit_cost')
                    ->label('Precio de compra por unidad')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                        if ($get('price_type') === true) {
                            $percentage = floatval($get('profit_percentage') ?? 0);
                            $purchasePrice = floatval($state ?? 0);
                            
                            if ($purchasePrice > 0 && $percentage > 0) {
                                $salePrice = $purchasePrice * (1 + ($percentage / 100));
                                $set('sales_price', round($salePrice, 2));
                            }
                        }
                    }),
                    
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
                    ->numeric()
                    ->suffix('%')
                    ->live(onBlur: true)
                    ->visible(fn (Get $get): bool => $get('price_type') === true)
                    ->required(fn (Get $get): bool => $get('price_type') === true)
                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                        $percentage = floatval($state ?? 0);
                        $purchasePrice = floatval($get('unit_cost') ?? 0);
                        
                        if ($purchasePrice > 0 && $percentage > 0) {
                            $salePrice = $purchasePrice * (1 + ($percentage / 100));
                            $set('sales_price', round($salePrice, 2));
                        }
                    }),
                    
                TextInput::make('sales_price')
                    ->label('Precio de venta')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->disabled(fn (Get $get): bool => $get('price_type') === true)
                    ->dehydrated(),
                    
                FileUpload::make('attachment')
                    ->label('Imagen del producto')
            ]);
    }
}