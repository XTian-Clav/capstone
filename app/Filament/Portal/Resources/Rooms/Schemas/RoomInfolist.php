<?php

namespace App\Filament\Portal\Resources\Rooms\Schemas;

use App\Models\Room;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class RoomInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make((fn ($record) => $record->room_name))
                ->schema([
                    ImageEntry::make('picture')
                        ->hiddenLabel()
                        ->disk('public')
                        ->visibility('public')
                        ->width(400)
                        ->height(160)
                        ->alignCenter()
                        ->defaultImageUrl(url('storage/default/no-image.png')),
                ])->columnSpan(1)->compact(),

                Section::make('Room Details')
                ->schema([
                    TextEntry::make('inclusions')
                        ->html()
                        ->extraAttributes([
                            'style' => 'text-align: justify; white-space: pre-line; word-break: break-word;',
                        ]),
                    
                    TextEntry::make('room_type')->weight('semibold'),
                    TextEntry::make('capacity')->weight('semibold'),
                    TextEntry::make('location')->weight('semibold'),

                    TextEntry::make('room_rate')
                        ->numeric()
                        ->numeric()
                        ->state(fn ($record) =>
                            ($record->room_rate && $record->room_rate > 0)
                                ? '₱' . number_format($record->room_rate)
                                : 'None'
                        )
                        ->weight('semibold'),
                    
                    TextEntry::make('is_available')
                        ->label('Availability')
                        ->state(fn ($record) => $record->is_available ? 'Room Available' : 'Room Unavailable')
                        ->color(fn ($record) => $record->is_available ? 'success' : 'danger')
                        ->weight('semibold'),
                    
                    TextEntry::make('deleted_at')
                        ->dateTime('M j, Y h:i A')
                        ->weight('semibold')
                        ->color('danger')
                        ->visible(fn (Room $record): bool => $record->trashed()),
                ])->columns(3)->columnSpan(2)->compact(),
            ])->columns(3);
    }
}
