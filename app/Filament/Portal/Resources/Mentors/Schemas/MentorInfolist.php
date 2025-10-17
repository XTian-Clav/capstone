<?php

namespace App\Filament\Portal\Resources\Mentors\Schemas;

use App\Models\Mentor;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

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
                        ->disk('public')
                        ->visibility('public')
                        ->size(200)
                        ->square()
                        ->alignCenter()
                        ->defaultImageUrl(url('storage/default/user.png')),

                    TextEntry::make('personal_info')
                        ->html()
                        ->extraAttributes([
                            'style' => 'text-align: justify; white-space: pre-line; word-break: break-word;',
                        ])
                        ->columnSpan(3),
                ])->columns(4)->columnSpan(3)->compact(),

                Section::make()
                ->schema([
                    TextEntry::make('name')->weight('semibold')->color('primary'),
                    TextEntry::make('contact')->weight('semibold'),
                    TextEntry::make('email')->weight('semibold'),
                    TextEntry::make('expertise')->weight('semibold'),
                    TextEntry::make('created_at')
                        ->dateTime('F j, Y h:i A')
                        ->weight('semibold')
                        ->label('Profile Creation'),
                    
                    TextEntry::make('deleted_at')
                        ->dateTime('M j, Y h:i A')
                        ->weight('semibold')
                        ->color('danger')
                        ->visible(fn (Mentor $record): bool => $record->trashed()),
                ])->columnSpan(3)->columns(3)->compact(),
            ])->columns(3);
    }
}
