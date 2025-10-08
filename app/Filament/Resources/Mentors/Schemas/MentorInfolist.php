<?php

namespace App\Filament\Resources\Mentors\Schemas;

use App\Models\Mentor;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;

class MentorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Mentor Details')
                ->schema([
                    ImageEntry::make('avatar')
                        ->label('Profile Picture')
                        ->disk('public')
                        ->visibility('public')
                        ->imageHeight(200),

                    TextEntry::make('personal_info')
                        ->markdown()
                        ->extraAttributes([
                            'style' => 'text-align: justify; white-space: pre-line; word-break: break-word;',
                        ])
                        ->columnSpan(3),
                ])->columns(4)->columnSpan(3),

                Section::make()
                ->schema([
                    TextEntry::make('firstname')->weight('bold'),
                    TextEntry::make('lastname')->weight('bold'),
                    TextEntry::make('contact')->weight('bold'),
                    TextEntry::make('email')->weight('bold'),
                    TextEntry::make('expertise')->weight('bold')->color('info'),
                    TextEntry::make('created_at')
                        ->dateTime('F j, Y h:i A')
                        ->weight('bold')
                        ->color('info')
                        ->label('Profile Creation'),
                ])->columnSpan(3)->columns(2),
            ])->columns(3);
    }
}
