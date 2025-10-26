<?php

namespace App\Filament\Portal\Resources\NonUsers\Schemas;

use App\Models\NonUser;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class NonUserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Profile')
                ->schema([
                    ImageEntry::make('avatar')
                        ->hiddenLabel()
                        ->disk('public')
                        ->visibility('public')
                        ->size(150)
                        ->square()
                        ->alignCenter()
                        ->columnSpan(1)
                        ->defaultImageUrl(url('storage/default/user.png')),

                    Grid::make()
                    ->schema([
                        TextEntry::make('name')->weight('semibold')->color('primary'),
                        TextEntry::make('contact')->weight('semibold'),
                        TextEntry::make('email')->weight('semibold'),
                        TextEntry::make('company')->weight('semibold'),
                    ])
                    ->columnSpan(3),
                ])->columns(4)->columnSpan(3)->compact()->secondary(),

                Section::make()
                ->schema([
                    TextEntry::make('role')->weight('semibold'),
                    
                    TextEntry::make('created_at')
                        ->dateTime('F j, Y h:i A')
                        ->weight('semibold')
                        ->label('Profile Creation'),
                    
                    TextEntry::make('deleted_at')
                        ->dateTime('M j, Y h:i A')
                        ->weight('semibold')
                        ->color('danger')
                        ->visible(fn (NonUser $record): bool => $record->trashed()),
                ])->columnSpan(3)->columns(3)->compact()->secondary(),
            ])->columns(3);
    }
}
