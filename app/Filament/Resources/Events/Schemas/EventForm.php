<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Storage;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                ->schema([
                    TextInput::make('event')->columnSpanFull()
                    ->required()
                    ->unique(),

                    MarkdownEditor::make('description')
                        ->required()
                        ->columnSpanFull()
                        ->toolbarButtons([
                            ['bold', 'italic', 'strike', 'link'],
                            ['heading','bulletList', 'orderedList'],
                            ['undo', 'redo'],
                        ]),

                    DateTimePicker::make('start_date')
                        ->default(now())
                        ->unique()
                        ->required()
                        ->Seconds(false)
                        ->native(false),

                    DateTimePicker::make('end_date')
                        ->required()
                        ->unique()
                        ->Seconds(false)
                        ->native(false),
                    
                    TextInput::make('location')
                        ->required(),

                    Select::make('status')
                        ->options(\App\Models\Event::STATUS)
                        ->default('Upcoming')
                        ->required()
                        ->native(false),
                ])->columnSpan(2)->columns(2),

                Section::make('Upload Event Poster')
                ->schema([
                    FileUpload::make('poster') ->label('Event Poster')
                    ->label('Event Poster')
                    ->default(null)
                    ->image()
                    ->imageEditor()

                    //IMG DIRECTORY
                    ->disk('public')
                    ->directory('events/posters')
                    ->visibility('public')

                    //FILE SIZE LIMIT
                    ->maxSize(8000),
                ]),
            ])->columns(3);
    }
}
