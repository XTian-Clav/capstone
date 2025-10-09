<?php

namespace App\Filament\Resources\Startups\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;

class StartupInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Startup Details')
                ->schema([
                    ImageEntry::make('logo')
                        ->label('Startup Logo')
                        ->disk('public')
                        ->visibility('public')
                        ->imageHeight(200),
                    TextEntry::make('description')
                        ->markdown()
                        ->extraAttributes([
                            'style' => 'text-align: justify; white-space: pre-line; word-break: break-word;',
                        ])
                        ->columnSpan(3),
                ])->columns(4)->columnSpan(3),
                
                Section::make()
                ->schema([
                    TextEntry::make('startup_name')
                        ->color('primary')
                        ->weight('semibold'),
                    
                    TextEntry::make('founder')
                        ->color('primary')
                        ->weight('semibold'),

                    TextEntry::make('mentors.fullname')
                        ->label('Mentors')
                        ->color('primary')
                        ->weight('semibold')
                        ->listWithLineBreaks(),
                    
                    TextEntry::make('submission_date')
                        ->dateTime('F j, Y h:i A')
                        ->badge()
                        ->color('secondary'),

                    TextEntry::make('status')
                        ->badge()
                        ->colors([
                            'warning' => 'Pending',
                            'success' => 'Approved',
                            'danger' => 'Rejected',
                        ]),
                    ])->columnSpan(3)->columns(3),
            ])->columns(3);
    }
}
