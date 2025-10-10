<?php

namespace App\Filament\Resources\Rooms\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\RichEditor;

class RoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Room Details')
                ->description('Fill up the form and make sure all details are correct.')
                ->schema([
                    TextInput::make('room_name')
                        ->unique()
                        ->required()
                        ->minLength(2)
                        ->maxLength(255),
                    
                    Select::make('room_type')
                        ->options(\App\Models\Room::ROOM_TYPE)
                        ->required()
                        ->native(false),
                    
                    TextInput::make('location')
                        ->required()
                        ->minLength(2)
                        ->maxLength(255),

                    TextInput::make('capacity')
                        ->required()
                        ->minLength(2)
                        ->maxLength(100),
                    
                    RichEditor::make('inclusions')
                        ->label('Inclusions')
                        ->default('<p><em>No Inclusions.</em></p>')
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
                
                ])->columnSpan(2)->columns(2)->compact(),

                Section::make('Photo Upload')
                ->schema([
                    FileUpload::make('picture')
                        ->label('Room Photo')
                        ->columnSpanFull()
                        ->image()
                        ->imageEditor()
                        
                        //IMG DIRECTORY
                        ->disk('public')
                        ->directory('room/pictures')
                        ->visibility('public')

                        //FILE SIZE LIMIT
                        ->maxSize(10000),
                    
                    TextInput::make('room_rate')
                        ->label('Room Rate')
                        ->prefix('₱')
                        ->numeric()
                        ->minValue(0)
                        ->default(0),
                    
                    Toggle::make('is_available')
                        ->label('Available')
                        ->default(true)
                        ->onColor('success')
                        ->offColor('danger')
                        ->inline(false)
                        ->reactive(),
                ])->columnSpan(1)->columns(2)->compact(),
            ])->columns(3);
    }
}
