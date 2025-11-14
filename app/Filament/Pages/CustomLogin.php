<?php

namespace App\Filament\Pages;

use Filament\Schemas\Schema;
use Filament\Forms\Components\ViewField;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;

class CustomLogin extends BaseLogin
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),

                // Terms and Conditions view only
                ViewField::make('terms')->view('livewire.terms'),
            ]);
    }

    public function authenticate(): ?LoginResponse
    {
        // No validation needed for the view
        return parent::authenticate();
    }
}