<?php

namespace App\Filament\Portal\Resources\Equipment\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

use Filament\Forms;
use Filament\Tables;
use Filament\Resource;
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

class EquipmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Equipment Details')
                ->description('Fill up the form and make sure all details are correct.')
                ->schema([
                    TextInput::make('equipment_name')
                        ->label('Equipment Name')
                        ->required(),
                    
                    TextInput::make('quantity')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->suffix('pcs'),
                    
                    TextInput::make('property_no')
                        ->required(),
                    
                    TextInput::make('remarks')
                        ->required()
                        ->default(null),

                    TextInput::make('location')
                        ->required(),
                ])->columnSpan(2)->columns(2)->compact(),

                Section::make('Photo Upload')
                ->schema([
                    FileUpload::make('picture')
                    ->label('Equipment Photo')
                    ->columnSpanFull()
                    ->image()
                    ->imageEditor()
                    
                    //IMG DIRECTORY
                    ->disk('public')
                    ->directory('equipment/pictures')
                    ->visibility('public')

                    //FILE SIZE LIMIT
                    ->maxSize(10000),
                ])->columnSpan(1)->columns(2)->compact(),
                
            ])->columns(3);
    }
}
