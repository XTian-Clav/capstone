<?php

namespace App\Filament\Portal\Resources\Startups\Schemas;

use App\Models\Startup;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

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
                        ->disk('public')
                        ->visibility('public')
                        ->imageHeight(200)
                        ->alignCenter()
                        ->defaultImageUrl(url('storage/default/no-image.png')),
                    TextEntry::make('description')
                        ->html()
                        ->extraAttributes([
                            'style' => 'text-align: justify; white-space: pre-line; word-break: break-word;',
                        ])
                        ->columnSpan(3),
                ])->columns(4)->columnSpan(3)->compact()->secondary(),
                
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
                    
                    TextEntry::make('contact')->weight('semibold'),
                    TextEntry::make('email')->weight('semibold'),

                    TextEntry::make('status')
                        ->badge()
                        ->colors([
                            'warning' => 'Pending',
                            'success' => 'Approved',
                            'danger' => 'Rejected',
                        ]),
                        
                    TextEntry::make('deleted_at')
                        ->dateTime('M j, Y h:i A')
                        ->weight('semibold')
                        ->color('danger')
                        ->visible(fn (Startup $record): bool => $record->trashed()),
                    ])->columnSpan(3)->columns(3)->compact()->secondary(),
            ])->columns(3);
    }
}
