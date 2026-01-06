<?php

namespace App\Filament\Resources\StockEntries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class StockEntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                self::informationSection(),
                self::productsSection(),
                self::summarySection(),
            ]);
    }

    private static function informationSection(): Section
    {
        return Section::make('Información del Ingreso')
            ->schema([
                TextInput::make('entry_number')
                    ->label('Número de Ingreso')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Se generará automáticamente'),

                DatePicker::make('entry_date')
                    ->label('Fecha de Ingreso')
                    ->required()
                    ->default(now())
                    ->native(false),

                Textarea::make('notes')
                    ->label('Notas')
                    ->rows(3)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    private static function productsSection(): Section
    {
        return Section::make('Productos')
            ->schema([
                Repeater::make('items')
                    ->relationship('items')
                    ->schema([
                        Select::make('product_id')
                            ->label('Producto')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->columnSpan(2),

                        TextInput::make('quantity')
                            ->label('Cantidad')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateSubtotal($get, $set);
                            }),

                        TextInput::make('unit_cost')
                            ->label('Costo Unitario')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->minValue(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateSubtotal($get, $set);
                            }),

                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated()
                            ->default(0),
                    ])
                    ->columns(4)
                    ->defaultItems(1)
                    ->addActionLabel('Agregar Producto')
                    ->reorderableWithButtons()
                    ->collapsible()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateTotal($get, $set);
                    })
                    ->deleteAction(
                        fn ($action) => $action->after(fn (Get $get, Set $set) => self::updateTotal($get, $set))
                    ),
            ]);
    }

    private static function summarySection(): Section
    {
        return Section::make('Resumen')
            ->schema([
                TextInput::make('total_display')
                    ->label('Costo Total')
                    ->prefix('$')
                    ->disabled()
                    ->dehydrated(false)
                    ->default(0),
            ])
            ->collapsible();
    }

    // Método para calcular subtotal de un item
    private static function updateSubtotal(Get $get, Set $set): void
    {
        $quantity = (float) ($get('quantity') ?? 0);
        $unitCost = (float) ($get('unit_cost') ?? 0);
        $subtotal = $quantity * $unitCost;

        $set('subtotal', number_format($subtotal, 2, '.', ''));
    }

    // Método para calcular el total de todos los items
    private static function updateTotal(Get $get, Set $set): void
    {
        $items = $get('items') ?? [];
        
        $total = collect($items)->sum(function ($item) {
            $quantity = (float) ($item['quantity'] ?? 0);
            $unitCost = (float) ($item['unit_cost'] ?? 0);
            return $quantity * $unitCost;
        });

        $set('total_display', number_format($total, 2, '.', ''));
    }
}
