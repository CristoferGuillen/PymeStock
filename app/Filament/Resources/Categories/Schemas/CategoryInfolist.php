<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles de la Categoría')
            ->schema([ 
                TextEntry::make('name')
                ->label('Nombre'),
                TextEntry::make('description')
                ->label('Descripción'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->icon('heroicon-o-calendar')
                    ->label('Creado El'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->icon('heroicon-o-calendar')
                    ->label('Actualizado El'),
            ]),
            ]);
    }
}
