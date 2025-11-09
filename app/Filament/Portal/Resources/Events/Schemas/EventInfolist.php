<?php

namespace App\Filament\Portal\Resources\Events\Schemas;

use App\Models\event;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make((fn ($record) => $record->event))
                ->schema([
                    ImageEntry::make('picture')
                        ->disk('public')
                        ->visibility('public')
                        ->hiddenLabel()
                        ->imageWidth(400)
                        ->imageHeight(200)
                        ->columnSpanFull()
                        ->defaultImageUrl(url('storage/default/no-image.png'))
                        ->extraImgAttributes([
                            'alt' => 'Logo',
                            'loading' => 'lazy',
                            'class' => 'rounded-xl object-cover',
                        ]),

                    TextEntry::make('description')
                        ->html()
                        ->hiddenLabel()
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

                        ])->columnSpan(3)->columns(2)->compact()->secondary(),

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
                    
                    TextEntry::make('deleted_at')
                        ->dateTime('M j, Y h:i A')
                        ->weight('semibold')
                        ->color('danger')
                        ->visible(fn (Event $record): bool => $record->trashed()),
                ])->columnSpan(1)->columns(3)->compact(),

                Section::make('Attendance List')
                ->schema([
                    RepeatableEntry::make('attendance')
                        ->hiddenLabel()
                        ->table([
                            TableColumn::make('Name'),
                            TableColumn::make('Going to Event'),
                        ])
                        ->schema([
                            TextEntry::make('user')
                                ->weight('semibold'),

                            TextEntry::make('status')
                                ->badge()
                                ->color('success'),
                        ])
                        ->columns(2),
                ])
                ->columnSpanFull()
                ->visible(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
            ])->columns(3);
    }
}
