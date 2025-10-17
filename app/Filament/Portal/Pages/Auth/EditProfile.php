<?php

namespace App\Filament\Portal\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                FileUpload::make('avatar_url')
                    ->hiddenLabel()
                    ->image()
                    ->avatar()
                    ->disk('public')
                    ->directory('user/avatar')
                    ->maxSize(5024)
                    ->imageEditor()
                    ->alignCenter()
                    ->columnSpanFull(),

                TextInput::make('name')
                    ->label('Full Name')
                    ->required(),

                $this->getEmailFormComponent(),
                
                TextInput::make('contact')
                    ->label('Contact Number'),

                TextInput::make('address')
                    ->label('Address'),

                DatePicker::make('birthdate')
                    ->label('Birthdate'),

                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
