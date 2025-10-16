<?php

namespace App\Filament\Portal\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('contact')
                    ->required(),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable(),
                TextInput::make('address')
                    ->required(),
                DatePicker::make('birthdate')
                    ->required(),
                TextInput::make('birthplace')
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make ($state))
                    ->dehydrated(fn (string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255)
                    ->revealable(),
            ]);
    }
}
