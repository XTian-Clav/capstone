<?php

namespace App\Filament\Portal\Resources\Users\Schemas;

use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile Picture')
                ->schema([
                    ImageEntry::make('avatar_url')
                        ->hiddenLabel()
                        ->disk('public')
                        ->visibility('public')
                        ->Height(230)
                        ->square()
                        ->alignCenter()
                        ->defaultImageUrl(url('storage/default/user.png')),
                ])->columnSpan(1)->compact()->secondary(),

                Section::make('Personal Details')
                ->schema([
                    Section::make()
                    ->schema([
                        TextEntry::make('name')->weight('semibold')->label('Name:')->inlineLabel(), 
                    
                        TextEntry::make('email')
                            ->label('Email address')
                            ->weight('semibold')
                            ->copyable()
                            ->copyMessage('Copied!')
                            ->copyMessageDuration(1500)
                            ->label('Email:')
                            ->inlineLabel(),

                        TextEntry::make('contact')
                            ->weight('semibold')
                            ->placeholder('N/A')
                            ->copyable()
                            ->copyMessage('Copied!')
                            ->copyMessageDuration(1500)
                            ->label('Contact:')
                            ->inlineLabel(),

                        TextEntry::make('company')->weight('semibold')->label('Company:')->placeholder('N/A')->inlineLabel(),
                    ])->compact(),
                    
                    Section::make()
                    ->schema([
                        TextEntry::make('email_verified_at')
                            ->dateTime('M j, Y h:i A')
                            ->placeholder('Unverified')
                            ->label('Email Status:')
                            ->inlineLabel(),
                    ])->compact(),

                    TextEntry::make('deleted_at')
                        ->dateTime('M j, Y h:i A')
                        ->weight('semibold')
                        ->color('danger')
                        ->label('Deleted At:')
                        ->inlineLabel()
                        ->visible(fn (User $record): bool => $record->trashed()),
                ])->columnSpan(2)->compact()->secondary(),
            ])->columns(3);
    }
}
