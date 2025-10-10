<?php

namespace App\Filament\Resources\Supplies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SupplyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('picture')
                    ->default(null),
                TextInput::make('item_name')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('reorder_level')
                    ->required()
                    ->numeric()
                    ->default(10),
                TextInput::make('location')
                    ->required(),
                TextInput::make('remarks')
                    ->required(),
            ]);
    }
}
