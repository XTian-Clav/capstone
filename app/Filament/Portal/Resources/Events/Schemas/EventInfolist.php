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
                            'style' => 'text-align: justify; word-break: break-word;',
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
                            'success' => 'Ongoing',
                            'cyan' => 'Completed',
                            'danger' => 'Cancelled',
                        ])
                        ->columnSpanFull()
                        ->inlineLabel()
                        ->label('Status:'),
                    
                    TextEntry::make('created_at')
                        ->badge()
                        ->inlineLabel()
                        ->columnSpanFull()
                        ->color('primary')
                        ->label('Create At:')
                        ->formatStateUsing(fn ($state) => $state?->format('M j, Y'))
                        ->tooltip(fn ($state) => $state?->format('M j, Y h:i A')),
                    
                    TextEntry::make('deleted_at')
                        ->dateTime('M j, Y h:i A')
                        ->weight('semibold')
                        ->inlineLabel()
                        ->color('danger')
                        ->label('Deleted At:')
                        ->visible(fn (Event $record): bool => $record->trashed()),
                ])->columnSpan(1)->compact(),

                Section::make('Attendance List')
                ->schema([
                    TextEntry::make('attendees.name')
                        ->label('Registered Attendees')
                        ->placeholder('No users have registered attendance yet.')
                        ->listWithLineBreaks(),

                    TextEntry::make('registration_dates')
                        ->label('Registration Dates')
                        ->hiddenLabel(false)
                        ->getStateUsing(function ($record) {
                            $registrations = $record->attendees()->get();
                            return $registrations->pluck('pivot.created_at');
                        })
                        ->dateTime('M j, Y h:i A')
                        ->listWithLineBreaks()
                        ->placeholder('—'),

                    TextEntry::make('attendance_statuses')
                        ->badge()
                        ->listWithLineBreaks()
                        ->label('Attendance Status')
                        ->getStateUsing(fn ($record) => $record->attendees()->get()->pluck('pivot.is_attending'))
                        ->formatStateUsing(fn ($state) => $state ? 'Attending' : 'Not Attending')
                        ->color(fn ($state) => $state ? 'success' : 'danger')
                        ->columnSpan(1),

                ])->columns(3)->columnSpanFull()->visible(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
            ])->columns(3);
    }
}
