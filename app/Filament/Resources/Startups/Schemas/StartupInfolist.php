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
                Section::make((fn ($record) => $record->startup_name))
                ->schema([
                    ImageEntry::make('logo')
                        ->label('Startup Logo')
                        ->disk('public')
                        ->visibility('public')
                        ->imageHeight(200)
                        ->columnSpanFull(),

                    TextEntry::make('startup_name')
                        ->weight('bold')
                        ->size('lg'),

                    TextEntry::make('founder')
                        ->weight('bold')
                        ->size('lg'),
                        
                    TextEntry::make('submission_date')
                        ->dateTime('F j, Y h:i A')
                        ->badge()
                        ->color('info'),

                    TextEntry::make('status')
                        ->badge()
                        ->colors([
                            'warning' => 'Pending',
                            'success' => 'Approved',
                            'danger' => 'Rejected',
                        ]),

                    TextEntry::make('created_at')
                        ->dateTime('F j, Y h:i A')
                        ->placeholder('-'),
                        
                    TextEntry::make('updated_at')
                        ->dateTime('F j, Y h:i A')
                        ->placeholder('-'),
                    ])->columnSpan(2)->columns(2),
            ]);
    }
}
