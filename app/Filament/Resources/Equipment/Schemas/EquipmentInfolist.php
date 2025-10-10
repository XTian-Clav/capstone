<?php

namespace App\Filament\Resources\Equipment\Schemas;

use App\Models\Equipment;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EquipmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('picture')
                    ->placeholder('-'),
                TextEntry::make('equipment_name'),
                TextEntry::make('quantity')
                    ->numeric(),
                TextEntry::make('reorder_level')
                    ->numeric(),
                TextEntry::make('property_no'),
                TextEntry::make('location'),
                TextEntry::make('remarks'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Equipment $record): bool => $record->trashed()),
            ]);
    }
}
