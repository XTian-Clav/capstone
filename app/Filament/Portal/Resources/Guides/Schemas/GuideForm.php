<?php

namespace App\Filament\Portal\Resources\Guides\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GuideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                TextInput::make('url')
                    ->url()
                    ->required(),
            ]);
    }
}
