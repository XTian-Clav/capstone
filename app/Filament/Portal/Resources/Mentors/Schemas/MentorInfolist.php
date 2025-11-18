<?php

namespace App\Filament\Portal\Resources\Mentors\Schemas;

use App\Models\Mentor;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;

class MentorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Mentor Details')
                ->schema([
                    ImageEntry::make('avatar')
                        ->hiddenLabel()
                        ->alignCenter()
                        ->columnSpan(1)
                        ->disk('public')
                        ->imageHeight(200)
                        ->visibility('public')
                        ->defaultImageUrl(url('storage/default/user.png'))
                        ->extraImgAttributes([
                            'alt' => 'Logo',
                            'loading' => 'lazy',
                            'class' => 'rounded-xl object-cover',
                        ]),
                    Section::make()
                    ->schema([
                        TextEntry::make('name')->label('Name:')->weight('semibold')->inlineLabel(),
                        TextEntry::make('email')->label('Email:')->weight('semibold')->inlineLabel(),
                        TextEntry::make('contact')->label('Contact:')->weight('semibold')->inlineLabel(),
                        TextEntry::make('expertise')->label('Expertise:')->weight('semibold')->inlineLabel(),
                        Section::make()
                        ->schema([
                            TextEntry::make('personal_info')
                                ->html()
                                ->extraAttributes([
                                    'style' => 'text-align: justify; word-break: break-word;',
                                ]),
                        ])->columnSpanFull()->compact(),
                    ])->columnSpan(4)->compact(),
                    
                    Section::make()
                    ->schema([
                        RepeatableEntry::make('startups')
                        ->label('Assigned Startups')
                        ->schema([
                            TextEntry::make('startup_name')
                                ->hiddenLabel()
                                ->columnSpanFull()
                                ->weight('semibold'),
                        ])->grid(4)->columns(4),
                    ])->columnSpanFull()->compact(),

                    Section::make()
                    ->schema([
                        RepeatableEntry::make('schedules')
                        ->schema([
                            TextEntry::make('day')->hiddenLabel()->weight('semibold')->columnSpan(2),
                            TextEntry::make('hour')->hiddenLabel(),
                            TextEntry::make('meridiem')->hiddenLabel(),
                        ])->grid(4)->columns(4),
                    ])->columnSpanFull()->compact(),

                    Section::make()
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime('F j, Y h:i A')
                            ->weight('semibold')
                            ->label('Profile Creation'),

                        TextEntry::make('updated_at')
                            ->dateTime('F j, Y h:i A')
                            ->weight('semibold')
                            ->label('Updated At'),
                        
                        TextEntry::make('deleted_at')
                            ->dateTime('M j, Y h:i A')
                            ->weight('semibold')
                            ->color('danger')
                            ->visible(fn (Mentor $record): bool => $record->trashed()),
                    ])->columnSpanFull()->columns(3)->compact(),
                ])->columns(5)->columnSpanFull()->compact(),
            ]);
    }
}
