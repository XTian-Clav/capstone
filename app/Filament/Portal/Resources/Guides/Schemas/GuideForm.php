<?php

namespace App\Filament\Portal\Resources\Guides\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\RichEditor;

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
                    
                    RichEditor::make('description')
                        ->label('Description')
                        ->default('<p><em>Enter description here.</em></p>')
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'strike',
                            'bulletList',
                            'orderedList',
                            'link',
                            'undo',
                            'redo',
                        ])
                        ->nullable()
                        ->required(),
                ])->columns(2)->columnSpanFull(),
            ]);
    }
}
