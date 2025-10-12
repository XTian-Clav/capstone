<?php

namespace App\Filament\Resources\ReserveEquipment\Schemas;

use App\Models\ReserveEquipment;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReserveEquipmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('reserved_by'),
                TextEntry::make('status')
                    ->columnSpanFull(),
                TextEntry::make('start_date')
                    ->dateTime(),
                TextEntry::make('end_date')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (ReserveEquipment $record): bool => $record->trashed()),
            ]);
    }
}
