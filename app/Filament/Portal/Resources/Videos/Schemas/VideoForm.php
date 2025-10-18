<?php

namespace App\Filament\Portal\Resources\Videos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VideoForm
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
