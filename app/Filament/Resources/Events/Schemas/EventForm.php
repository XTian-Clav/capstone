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

                    Textarea::make('description')
                        ->required()
                        ->columnSpanFull(),

                    DateTimePicker::make('start_date')
                        ->default(now())
                        ->required()
                        ->displayFormat('F j, Y h:i A')
                        ->minutesStep(15)
                        ->Seconds(false)
                        ->native(false),

                    DateTimePicker::make('end_date')
                        ->required()
                        ->displayFormat('F j, Y h:i A')
                        ->minutesStep(15)
                        ->Seconds(false)
                        ->native(false),
                    
                    TextInput::make('location')
                        ->required(),

                    Select::make('status')
                        ->options(\App\Models\Event::STATUS)
                        ->default('Pending')
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
