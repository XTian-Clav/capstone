<?php

namespace App\Filament\Portal\Resources\Events\Schemas;

use Carbon\Carbon;
use App\Models\Event;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DateTimePicker;

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
                    ->unique()
                    ->minLength(2)
                    ->maxLength(255),

                    RichEditor::make('description')
                        ->label('Description')
                        ->default(null)
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
                        ]),
                    
                    TextInput::make('location')
                        ->required()
                        ->minLength(2)
                        ->maxLength(255),

                    Select::make('status')
                        ->options(Event::STATUS)
                        ->default('Upcoming')
                        ->required()
                        ->native(false),
                ])->columnSpan(2)->columns(2)->compact(),

                Section::make('Upload Event Banner')
                ->schema([
                    FileUpload::make('picture')
                    ->label('Event Banner')
                    ->default(null)
                    ->image()
                    ->imageEditor()

                    //IMG DIRECTORY
                    ->disk('public')
                    ->directory('events/picture')
                    ->visibility('public')

                    //FILE SIZE LIMIT
                    ->maxSize(8000),

                    DateTimePicker::make('start_date')
                        ->displayFormat('F j, Y — h:i A')
                        ->default(now())
                        ->required()
                        ->Seconds(false)
                        ->native(false),

                    DateTimePicker::make('end_date')
                        ->displayFormat('F j, Y — h:i A')
                        ->required()
                        ->Seconds(false)
                        ->native(false)
                        ->rule(function ($get) {
                            return function (string $attribute, $value, $fail) use ($get) {
                                $end = Carbon::parse($value);
                                $hour = $end->format('H');
                    
                                // Ensure end > start
                                if ($get('start_date')) {
                                    $start = Carbon::parse($get('start_date'));
                                    if ($end->lessThanOrEqualTo($start)) {
                                        $fail('[Invalid] End time is earlier than the start time.');
                                    }
                                }
                            };
                        }),
                ])->compact(),
            ])->columns(3);
    }
}
