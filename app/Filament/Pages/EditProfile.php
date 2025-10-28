<?php

namespace App\Filament\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Photo Upload')
                    ->aside()
                    ->description('Manage your personal information.')
                    ->schema([
                    FileUpload::make('avatar_url')
                        ->hiddenLabel()
                        ->default(null)
                        ->avatar()
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
                        ->maxSize(5120),

                    Grid::make()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                    ])->columnSpan(2)->columns(1),
                ])->columns(3),

                Section::make('Edit Contact Details')
                    ->aside()
                    ->description('Manage your contact details. Make sure that your contact number is unique')
                    ->schema([
                        TextInput::make('contact')->unique()->required(),
                        TextInput::make('company')->unique()->required(),
                ]),
                
                Section::make('Change Password')
                    ->aside()
                    ->description('Manage your account password. Make sure to verify the new password.')
                    ->schema([
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                ]),
            ]);
    }
}
