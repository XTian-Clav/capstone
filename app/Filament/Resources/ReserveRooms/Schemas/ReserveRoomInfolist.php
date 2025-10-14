<?php

namespace App\Filament\Resources\ReserveRooms\Schemas;

use App\Models\ReserveRoom;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReserveRoomInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('reserved_by'),
                TextEntry::make('room_id')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('office'),
                TextEntry::make('contact'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('start_date')
                    ->dateTime(),
                TextEntry::make('end_date')
                    ->dateTime(),
                IconEntry::make('accept_terms')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (ReserveRoom $record): bool => $record->trashed()),
            ]);
    }
}
