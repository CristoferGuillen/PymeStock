<?php

namespace App\Filament\Resources\Sales\Schemas;

use App\Models\Product;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;


class SaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Información del Cliente')
                    ->schema([
                        TextInput::make('customer_name')
                            ->label('Nombre del Cliente')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('customer_email')
                            ->label('Email del Cliente')
                            ->email()
                            ->maxLength(255),

                        DateTimePicker::make('sale_date')
                            ->label('Fecha de Venta')
                            ->default(now())
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpan(2),

                Section::make('Productos')
                    ->schema([
                        Repeater::make('items')
                            ->schema([
                                Select::make('product_id')
                                    ->label('Producto')
                                    ->options(
                                        Product::query()
                                            ->where('units_available', '>', 0)
                                            ->pluck('name', 'id')
                                    )
                                    ->searchable()
                                    ->preload() 
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if (! $state) {
                                            return;
                                        }

                                        $product = Product::find($state);
                                        if (! $product) {
                                            return;
                                        }

                                        $set('quantity', 1);
                                        $set('unit_price', $product->sales_price);
                                        $set('available_stock', $product->units_available);
                                        $set('subtotal', $product->sales_price);
                                        
                                        self::updateTotals($get, $set);
                                    })
                                    ->columnSpan(3),

                                TextInput::make('available_stock')
                                    ->label('Stock Disponible')
                                    ->readOnly()   
                                    ->disabled()        
                                    ->dehydrated(false)    
                                    ->columnSpan(1),

                                TextInput::make('quantity')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->maxValue(999)       
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $qty = max(1, (int) ($state ?? 1));
                                        
                                        $stock = (int) ($get('available_stock') ?? 0);
                                        if ($qty > $stock) {
                                            $qty = $stock;
                                            Notification::make()
                                                ->warning()
                                                ->title('Stock insuficiente')
                                                ->body("Solo hay {$stock} unidades disponibles")
                                                ->send();
                                        }

                                        $price = (float) ($get('unit_price') ?? 0);
                                        $set('quantity', $qty);
                                        $set('subtotal', round($qty * $price, 2));
                                        
                                        self::updateTotals($get, $set);
                                    })
                                    ->columnSpan(2),

                                TextInput::make('unit_price')
                                    ->label('Precio Unitario')
                                    ->numeric()
                                    ->prefix('$')
                                    ->minValue(0.01)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $price = max(0.01, (float) ($state ?? 0));
                                        $qty = max(1, (int) ($get('quantity') ?? 1));
                                        $set('subtotal', round($qty * $price, 2));
                                        
                                        self::updateTotals($get, $set);
                                    })
                                    ->columnSpan(2),

                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('$')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(2),
                            ])
                            ->columns(10)
                            ->defaultItems(1)
                            ->reorderable(false)
                            ->addActionLabel('Agregar Producto')
                            ->deleteAction(fn ($action) => $action->requiresConfirmation())
                            ->reactive()
                            ->afterStateUpdated(function ($get, $set) {
                                self::updateTotals($get, $set);
                            })
                            ->columnSpan(2),
                    ])
                    ->columnSpan(2),

                Section::make('Totales')
                    ->schema([
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->prefix('$')
                            ->numeric()
                            ->readOnly()
                            ->disabled()
                            ->dehydrated()
                            ->extraAttributes(['class' => 'font-semibold']),

                        TextInput::make('total')
                            ->label('Total')
                            ->prefix('$')
                            ->numeric()
                            ->readOnly()
                            ->disabled()
                            ->dehydrated()
                            ->extraAttributes(['class' => 'font-bold text-xl bg-gray-50 border-2 border-gray-200 rounded-lg']),
                    ])
                    ->columns(2)
                    ->columnSpan(2),
            ]);
    }

    protected static function updateTotals($get, $set): void
    {
        $items = $get('../../items');
        
        $subtotal = 0;
        
        if (is_array($items)) {
            foreach ($items as $item) {
                if (isset($item['subtotal']) && is_numeric($item['subtotal'])) {
                    $subtotal += (float) $item['subtotal'];
                }
            }
        }
        
        $set('../../subtotal', round($subtotal, 2));
        $set('../../total', round($subtotal, 2));
    }
}
