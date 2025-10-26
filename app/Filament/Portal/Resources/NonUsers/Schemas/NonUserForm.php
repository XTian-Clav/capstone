<?php

namespace App\Filament\Portal\Resources\NonUsers\Schemas;

use App\Models\NonUser;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

class NonUserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Non-user accounts')
                ->description('These are users that doesnt have a systems accounts. It is intended for external reservators.')
                ->schema([
                    TextInput::make('name')
                        ->unique()
                        ->required()
                        ->minLength(2)
                        ->maxLength(255)
                        ->columnSpanFull(),

                    TextInput::make('contact')
                        ->required()
                        ->unique()
                        ->tel()
                        ->minLength(11)
                        ->helperText('Enter a valid phone number (11 digits).'),
                    
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true),

                    TextInput::make('company')
                        ->required()
                        ->minLength(2)
                        ->maxLength(100),

                    Select::make('role')
                        ->options(NonUser::ROLE)
                        ->required()
                        ->native(false),
                ])->columnSpan(2)->columns(2)->compact(),
                
                Section::make('Photo Upload')
                ->schema([
                    FileUpload::make('avatar')
                        ->label('Profile Photo')
                        ->image()
                        ->imageEditor()
                        
                        //IMG DIRECTORY
                        ->disk('public')
                        ->directory('non_user/avatar')
                        ->visibility('public')

                        //IMAGE CROP (1:1)
                        ->imageCropAspectRatio('1:1')
                        ->imageResizeMode('cover')

                        //FILE SIZE LIMIT
                        ->maxSize(5120)
                        ->helperText('Optional. Can be left blank.'),
                ])->compact(),
            ])->columns(3);
    }
}
