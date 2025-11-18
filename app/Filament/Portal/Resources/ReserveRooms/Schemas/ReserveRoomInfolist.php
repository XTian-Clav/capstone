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
                Section::make(fn ($record) => $record->room?->room_type ?? 'Room')
                ->schema([
                    ImageEntry::make('picture')
                        ->getStateUsing(fn ($record) => $record->room?->picture)
                        ->disk('public')
                        ->hiddenLabel()
                        ->width(400)
                        ->height(160)
                        ->columnSpanFull()
                        ->alignCenter()
                        ->defaultImageUrl(url('storage/default/no-image.png'))
                        ->extraImgAttributes([
                            'alt' => 'Logo',
                            'loading' => 'lazy',
                            'class' => 'rounded-xl object-cover',
                        ]),

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
                    Section::make('Admin Comment')
                    ->schema([
                        TextEntry::make('admin_comment')->hiddenLabel()->color('danger'),
                    ])->columnSpanFull()->compact()->collapsed(true),
                ])->columnSpan(1)->columns(2)->compact()->secondary(),
                
                Section::make('Reservation Details')
                ->schema([
                    Section::make()
                    ->schema([
                        TextEntry::make('reserved_by')->weight('semibold')->label('Reserved By:')->inlineLabel(),
                        TextEntry::make('email')->weight('semibold')->label('Email:')->inlineLabel(),
                        TextEntry::make('contact')->weight('semibold')->label('Contact:')->inlineLabel(),
                        TextEntry::make('company')->weight('semibold')->label('Company:')->inlineLabel(),
                    ])->columnSpanFull()->compact(),
                
                    Section::make()
                    ->schema([
                        TextEntry::make('start_date')
                            ->dateTime('M j, Y h:i A')
                            ->label('Start Date:')
                            ->weight('semibold')
                            ->inlineLabel(),
                        
                        TextEntry::make('end_date') 
                            ->dateTime('M j, Y h:i A')
                            ->label('End Date:')
                            ->weight('semibold')
                            ->inlineLabel(),
                        
                        TextEntry::make('created_at')
                            ->dateTime('M j, Y h:i A')
                            ->label('Submission Date:')
                            ->weight('semibold')
                            ->inlineLabel(),
                        
                        TextEntry::make('deleted_at')
                            ->dateTime('M j, Y h:i A')
                            ->weight('semibold')
                            ->color('danger')
                            ->label('Deleted At:')
                            ->inlineLabel()
                            ->visible(fn (ReserveRoom $record): bool => $record->trashed()),
                    ])->columnSpanFull()->compact(),
                ])->columnSpan(2)->compact()->secondary(),
            ])->columns(3);
    }
}
