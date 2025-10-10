<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;
use Illuminate\Support\HtmlString;

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
                        ->html()
                        ->columnSpanFull()
                        ->extraAttributes([
                            'style' => 'text-align: justify; white-space: pre-line; word-break: break-word;',
                        ]),
                ])->columnSpan(2)->columns(2)->compact(),

                Section::make('Details')
                ->schema([
                    Section::make()
                    ->schema([
                        TextEntry::make('location')
                            ->weight('semibold')
                            ->columnSpanFull(),

                        TextEntry::make('start_date')
                            ->dateTime('M j, Y h:i A')
                            ->badge()
                            ->color('success'),
                        
                        TextEntry::make('end_date')
                            ->dateTime('M j, Y h:i A')
                            ->badge()
                            ->color('danger'),

                        ])->columnSpan(3)->columns(2)->secondary()->compact(),

                    TextEntry::make('status')
                        ->badge()
                        ->colors([
                            'warning' => 'Upcoming',
                            'info' => 'Ongoing',
                            'success' => 'Completed',
                            'danger' => 'Cancelled',
                        ]),
                    
                    TextEntry::make('created_at')
                        ->badge()
                        ->color('secondary')
                        ->formatStateUsing(fn ($state) => $state?->format('M j, Y'))
                        ->tooltip(fn ($state) => $state?->format('M j, Y h:i A')),
                    
                    TextEntry::make('updated_at')
                        ->badge()
                        ->color('secondary')
                        ->formatStateUsing(fn ($state) => $state?->format('M j, Y'))
                        ->tooltip(fn ($state) => $state?->format('M j, Y h:i A')),
                ])->columnSpan(1)->columns(3)->compact(),

            ])->columns(3);
    }
}
