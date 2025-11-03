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
                Section::make()
                ->schema([
                    ImageEntry::make('logo')
                        ->hiddenLabel()
                        ->disk('public')
                        ->visibility('public')
                        ->imageHeight(200)
                        ->alignCenter()
                        ->defaultImageUrl(url('storage/default/no-image.png')),

                    TextEntry::make('deleted_at')
                        ->dateTime('M j, Y h:i A')
                        ->weight('semibold')
                        ->color('danger')
                        ->visible(fn (Startup $record): bool => $record->trashed()),
                ])->compact(),
                
                Section::make()
                ->schema([
                    TextEntry::make('startup_name')
                        ->color('primary')
                        ->weight('semibold'),
                    
                    TextEntry::make('founder')
                        ->color('primary')
                        ->weight('semibold'),

                    TextEntry::make('status')
                        ->badge()
                        ->colors([
                            'warning' => 'Pending',
                            'success' => 'Approved',
                            'danger' => 'Rejected',
                        ]),

                    Section::make()
                    ->schema([
                        TextEntry::make('description')
                            ->html()
                            ->extraAttributes([
                                'style' => 'text-align: justify; white-space: pre-line; word-break: break-word;',
                            ])
                            ->columnSpan(3),
                        ])->columnSpanFull()->compact(),
                ])->columns(5)->columnSpan(2)->compact(),

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
            ])->columns(3);
    }
}
