<?php

namespace App\Filament\Portal\Resources\Guides\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class GuideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Upload Document')
                ->Schema([
                    TextInput::make('title')
                        ->required()
                        ->unique()
                        ->placeholder('Enter title here'),

                    TextInput::make('url')
                        ->url()
                        ->required()
                        ->prefix('Link')
                        ->suffixIcon('heroicon-m-link')
                        ->placeholder('Enter drive link here'),
                    
                    Textarea::make('description')
                        ->default('Enter description here.')
                        ->columnSpanFull()
                        ->rows(5),
                ])->columns(2)->columnSpanFull(),
            ]);
    }
}
