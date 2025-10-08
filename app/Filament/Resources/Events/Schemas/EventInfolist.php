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
                        ->imageHeight(200)
                        ->columnSpanFull(),

                    TextEntry::make('description')
                        ->markdown()
                        ->columnSpanFull()
                        ->extraAttributes([
                            'style' => 'text-align: justify; white-space: pre-line; word-break: break-word;',
                        ]),
                ])->columnSpan(2)->columns(2),

                Section::make('Details')
                ->schema([
                    TextEntry::make('location')
                        ->weight('bold'),

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
                        ->dateTime('M j, Y h:i A')
                        ->color('success'),

                    TextEntry::make('end_date')
                        ->badge()
                        ->dateTime('M j, Y h:i A')
                        ->color('danger'),
                    ])->columnSpan(1)->columns(2),
            ])->columns(3);
    }
}
