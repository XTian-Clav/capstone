<?php

namespace App\Filament\Portal\Resources\Announcements\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\RichEditor;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                ->schema([
                    TextInput::make('title')
                        ->required(),
                    
                    Textarea::make('content')
                        ->required()
                        ->default('Enter content here.')
                        ->rows(10)
                        ->cols(20),
                ])->columnSpanFull()
            ]);
    }
}
