<?php

namespace App\Filament\Portal\Resources\Users\Schemas;

use Carbon\Carbon;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                ->schema([
                    Section::make('Photo Upload')
                    ->schema([
                        FileUpload::make('avatar_url')
                            ->hiddenLabel()
                            ->default(null)
                            ->image()
                            ->imageEditor()

                            //IMG DIRECTORY
                            ->disk('public')
                            ->directory('avatars')
                            ->visibility('public')

                            //IMAGE CROP (1:1)
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeMode('cover')

                            //FILE SIZE LIMIT
                            ->maxSize(5120)
                            ->imagePreviewHeight('185'),
                    ])->columnSpan(1)->compact()->secondary(),

                    Section::make()
                    ->schema([
                        Section::make() 
                        ->schema([
                            CheckboxList::make('roles')
                                ->relationship('roles', 'name')
                                ->maxItems(1)
                                ->required()
                                ->columns(2),
                        ])->columnSpanFull(),
                        DateTimePicker::make('email_verified_at')
                            ->native(false)
                            ->label('Email Status')
                            ->placeholder('Unverified'),
                        
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn (?string $state): ?string => $state ? Hash::make($state) : null)
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->maxLength(100)
                            ->revealable(),
                    ])->columnSpan(2)->columns(2)->compact()->secondary(),

                
                    Grid::make()
                    ->schema([
                        TextInput::make('name')->unique()->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->unique()
                            ->required(),
                        
                        TextInput::make('contact')->unique()->required(),
                        TextInput::make('company')->required(),
                    ])->columnSpanFull()->columns(2),
            ])->columnSpanFull()->columns(3)->compact(),
        ]);
    }
}
