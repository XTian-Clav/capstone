<?php

namespace App\Filament\Portal\Resources\Startups\Schemas;

use App\Models\Startup;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;

class StartupInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Startup Details')
                ->schema([
                    ImageEntry::make('logo')
                        ->hiddenLabel()
                        ->alignCenter()
                        ->columnSpan(1)
                        ->disk('public')
                        ->imageHeight(200)
                        ->visibility('public')
                        ->defaultImageUrl(url('storage/default/no-image.png'))
                        ->extraImgAttributes([
                            'alt' => 'Logo',
                            'loading' => 'lazy',
                            'class' => 'rounded-xl object-cover',
                        ]),
                    
                    Section::make()
                    ->schema([
                        TextEntry::make('startup_name')
                            ->weight('semibold'),
                        
                        TextEntry::make('founder')
                            ->weight('semibold'),

                        TextEntry::make('status')
                            ->badge()
                            ->colors([
                                'warning' => 'Pending',
                                'success' => 'Approved',
                                'danger' => 'Rejected',
                            ]),

                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->since()
                            ->badge()
                            ->color('success')
                            ->tooltip(fn ($record) => $record->created_at->format('F j, Y g:i A')),

                        TextEntry::make('deleted_at')
                            ->dateTime('M j, Y h:i A')
                            ->badge()
                            ->color('danger')
                            ->visible(fn (Startup $record): bool => $record->trashed()),

                        Section::make()
                        ->schema([
                            TextEntry::make('description')
                                ->html()
                                ->extraAttributes([
                                    'style' => 'text-align: justify; white-space: pre-line; word-break: break-word;',
                                ])
                                ->columnSpan(3),
                            ])->columnSpanFull()->compact(),
                    ])->columns(5)->columnSpan(4)->compact(),
                    Section::make()
                    ->schema([
                        RepeatableEntry::make('members')
                        ->schema([
                            TextEntry::make('name')
                            ->hiddenLabel()
                            ->weight('semibold')
                            ->color('primary')
                            ->columnSpanFull(),
                        ])->grid(4)->columns(4),
                    ])->columnSpanFull()->compact(),

                    Section::make()
                    ->schema([
                        RepeatableEntry::make('mentors')
                        ->schema([
                            TextEntry::make('name')
                                ->hiddenLabel()
                                ->columnSpanFull()
                                ->weight('semibold'),
                        ])->grid(4)->columns(4),
                    ])->columnSpanFull()->compact(),
                ])->columns(5)->columnSpanFull()->compact(),            
            ])->columns(5);
    }
}
