<?php

namespace App\Filament\Portal\Resources\Rooms\Schemas;

use Filament\Forms;
use App\Models\Room;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DateTimePicker;

class RoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Room Details')
                ->description('Fill up the form and make sure all details are correct.')
                ->schema([
                    Select::make('room_type')
                        ->options(Room::ROOM_TYPE)
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
