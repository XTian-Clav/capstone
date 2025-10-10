<?php

namespace App\Filament\Resources\Supplies\Schemas;

use App\Models\Supply;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SupplyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('picture')
                    ->placeholder('-'),
                TextEntry::make('item_name'),
                TextEntry::make('quantity')
                    ->numeric(),
                TextEntry::make('reorder_level')
                    ->numeric(),
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
                    ->visible(fn (Supply $record): bool => $record->trashed()),
            ]);
    }
}
