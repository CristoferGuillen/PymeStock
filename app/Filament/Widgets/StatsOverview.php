<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Sale;

class StatsOverview extends BaseWidget
{


        protected function getStats(): array
        {
            $totaProducts = Product::count();
            $totalUnits = Product::sum('units_available');
            $lowStock = Product::where('units_available', '<', 10)->count();
            $todaySales = Sale::whereDate('created_at', today())->count();
            $todayRevenue = Sale::whereDate('created_at', today())->sum('total');

            return [
                Stat::make('Total De Productos', $totaProducts)
                    ->description('Productos en inventario')
                    ->descriptionIcon('heroicon-o-archive-box')
                    ->color('success'),
                Stat::make('Unidades Disponibles', $totalUnits)
                    ->description('Total de unidades en inventario')
                    ->descriptionIcon('heroicon-o-archive-box-arrow-down')
                    ->color('info'),
                Stat::make('Stock Bajo', $lowStock)
                    ->description('Productos con bajo inventario')
                    ->descriptionIcon('heroicon-o-exclamation-triangle')
                    ->color('warning'),
                Stat::make('Ventas Hoy', $todaySales)
                    ->description('Ventas realizadas hoy')
                    ->descriptionIcon('heroicon-o-shopping-cart')
                    ->color('info'),
            Stat::make('Ingresos Hoy', '$' . number_format($todayRevenue, 0, ',', '.'))
                ->description('Total vendido hoy')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),
            ];
    }
}
