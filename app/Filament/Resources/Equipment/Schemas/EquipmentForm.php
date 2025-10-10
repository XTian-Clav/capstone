<?php

namespace App\Filament\Resources\Equipment\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EquipmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('picture')
                    ->default(null),
                TextInput::make('equipment_name')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('reorder_level')
                    ->required()
                    ->numeric()
                    ->default(5),
                TextInput::make('property_no')
                    ->required(),
                TextInput::make('location')
                    ->required(),
                TextInput::make('remarks')
                    ->required(),
            ]);
    }
}
