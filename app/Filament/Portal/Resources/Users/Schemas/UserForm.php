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
                    Section::make()
                    ->schema([
                        FileUpload::make('avatar_url')
                            ->hiddenLabel()
                            ->default(null)
                            
                            //IMG EDITOR
                            ->image()
                            ->imageEditor()
                            ->panelAspectRatio('2:1')
                            ->panelLayout('integrated')

                            //IMG DIRECTORY
                            ->disk('public')
                            ->directory('avatars')
                            ->visibility('public')

                            //IMAGE CROP (1:1)
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeMode('cover')

                            //FILE SIZE LIMIT
                            ->maxSize(5120),
                        Section::make() 
                        ->schema([
                            CheckboxList::make('roles')
                                ->relationship('roles', 'name')
                                ->maxItems(1)
                                ->required()
                                ->columns(2),
                        ])->columnSpan(2),
                    ])->columnSpanFull()->columns(3)->compact()->secondary(),

                
                    Grid::make()
                    ->schema([
                        TextInput::make('name')->unique()->required()->placeholder('Enter fullname'),
                        
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->unique()
                            ->required()
                            ->placeholder('Enter email'),
                        
                        TextInput::make('contact')
                            ->unique()
                            ->required()
                            ->mask('0999-999-9999')
                            ->placeholder('09XX-XXX-XXXX'),
                        
                        TextInput::make('company')->required()->placeholder('Enter company'),

                        TextInput::make('password')
                            ->password()
                            ->placeholder('Enter password')
                            ->dehydrateStateUsing(fn (?string $state): ?string => $state ? Hash::make($state) : null)
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->maxLength(100)
                            ->revealable(),
                        
                        DateTimePicker::make('email_verified_at')
                            ->native(false)
                            ->disabled()
                            ->label('Email Status')
                            ->placeholder('Unverified'),
                    ])->columnSpanFull()->columns(2),
            ])->columnSpanFull()->columns(3)->compact(),
        ]);
    }
}
