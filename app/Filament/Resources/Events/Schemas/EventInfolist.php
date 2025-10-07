<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make((fn ($record) => $record->event))
                ->schema([
                    ImageEntry::make('poster')
                        ->disk('public')
                        ->visibility('public')
                        ->alignCenter()
                        ->imageHeight(200)
                        ->columnSpanFull(),

                    TextEntry::make('description')
                        ->markdown()
                        ->columnSpanFull(),
                ])->columnSpan(2)->columns(2),

                Section::make('Details')
                ->schema([
                    TextEntry::make('location')
                        ->weight('bold')
                        ->size('lg'),

                    TextEntry::make('status')
                        ->badge()
                        ->colors([
                            'warning' => 'Upcoming',
                            'success' => 'Completed',
                            'info' => 'Ongoing',
                            'danger' => 'Cancelled',
                        ]),

                    TextEntry::make('start_date')
                        ->badge()
                        ->dateTime('F j, Y h:i A')
                        ->color('success'),

                    TextEntry::make('end_date')
                        ->badge()
                        ->dateTime('F j, Y h:i A')
                        ->color('danger'),

                    TextEntry::make('created_at')
                        ->dateTime('F j, Y h:i A')
                        ->placeholder('-'),

                    TextEntry::make('updated_at')
                        ->dateTime('F j, Y h:i A')
                        ->placeholder('-'),

                    ])->columnSpan(1)->columns(1),
            ])->columns(3);
    }
}
