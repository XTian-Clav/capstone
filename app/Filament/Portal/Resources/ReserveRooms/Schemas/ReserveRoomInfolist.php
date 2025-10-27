<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Schemas;

use App\Models\Room;
use App\Models\ReserveRoom;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Support\Facades\Storage;

class ReserveRoomInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(fn ($record) => $record->room?->room_name ?? 'Room')
                ->schema([
                    ImageEntry::make('picture')
                        ->getStateUsing(fn ($record) => $record->room?->picture) // just the stored path
                        ->disk('public')
                        ->hiddenLabel()
                        ->width(400)
                        ->height(160)
                        ->columnSpanFull()
                        ->alignCenter()
                        ->defaultImageUrl(url('storage/default/no-image.png')),

                    Section::make()
                    ->schema([
                        TextEntry::make('Capacity')
                            ->getStateUsing(fn ($record) => $record->room?->capacity)
                            ->weight('semibold')
                            ->badge()
                            ->color('info'),
                        
                        TextEntry::make('status')
                            ->weight('semibold')
                            ->badge()
                            ->colors([
                                'warning' => 'Pending',
                                'success' => 'Approved',
                                'danger' => 'Rejected',
                            ]),
                    ])->columnSpanFull()->columns(2)->compact(),
                ])->columnSpan(1)->columns(2)->compact()->secondary(),
                
                    Section::make('Reservation Details')
                    ->schema([
                        Section::make()
                        ->schema([
                            TextEntry::make('reserved_by')->weight('semibold'),
                            TextEntry::make('email')->weight('semibold')->columnSpan(2),
                            TextEntry::make('contact')->weight('semibold'),
                            TextEntry::make('company')->weight('semibold')->columnSpan(2),
                        ])->columnSpanFull()->columns(3)->compact(),
                    
                        Section::make()
                        ->schema([
                            TextEntry::make('start_date')
                                ->dateTime('M j, Y h:i A')
                                ->weight('semibold'),
                            
                            TextEntry::make('end_date') 
                                ->dateTime('M j, Y h:i A')
                                ->weight('semibold'),
                            
                            TextEntry::make('created_at')
                                ->dateTime('M j, Y h:i A')
                                ->label('Submittion Date')
                                ->weight('semibold'),
                            
                            TextEntry::make('deleted_at')
                                ->dateTime('M j, Y h:i A')
                                ->weight('semibold')
                                ->color('danger')
                                ->visible(fn (ReserveRoom $record): bool => $record->trashed()),
                        ])->columnSpanFull()->columns(2)->compact(),
                ])->columns(3)->columnSpan(2)->compact()->secondary(),
            ])->columns(3);
    }
}
