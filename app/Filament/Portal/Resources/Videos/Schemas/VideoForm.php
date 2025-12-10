<?php

namespace App\Filament\Portal\Resources\Videos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

class VideoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Upload Video')
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
                ])->columns(2)->columnSpan(2),
                Section::make('Thumbnail')
                ->schema([
                    FileUpload::make('picture')
                        ->image()
                        ->imageEditor()
                        ->hiddenLabel()
                        ->default(null)
                        ->helpertext('Note: Thumbnails are optional. You can leave it blank.')

                        //IMG DIRECTORY
                        ->disk('public')
                        ->directory('videos/picture')
                        ->visibility('public')

                        //FILE SIZE LIMIT
                        ->maxSize(5000),
                ])->columnSpan(1),
            ])->columns(3);
    }
}
