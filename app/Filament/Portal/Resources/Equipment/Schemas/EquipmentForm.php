<?php

namespace App\Filament\Portal\Resources\Equipment\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

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
                        ->placeholder('Enter equipment name')
                        ->required()
                        ->unique(),
                    
                    TextInput::make('quantity')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->suffix('pcs'),
                    
                    TextInput::make('property_no')
                        ->required()
                        ->placeholder('Enter property number')
                        ->unique(),

                    TextInput::make('location')
                        ->required()
                        ->placeholder('Enter  location details'),
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
