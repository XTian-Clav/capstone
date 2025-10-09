<?php

namespace App\Filament\Resources\Mentors\Schemas;

use App\Models\Mentor;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;
use Illuminate\Support\HtmlString;

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
                    TextEntry::make('firstname')->weight('semibold')->color('primary'),
                    TextEntry::make('lastname')->weight('semibold')->color('primary'),
                    TextEntry::make('contact')->weight('semibold'),
                    TextEntry::make('email')->weight('semibold'),
                    TextEntry::make('expertise')->weight('semibold'),
                    TextEntry::make('created_at')
                        ->dateTime('F j, Y h:i A')
                        ->weight('semibold')
                        ->label('Profile Creation'),
                ])->columnSpan(3)->columns(2),
            ])->columns(3);
    }
}
