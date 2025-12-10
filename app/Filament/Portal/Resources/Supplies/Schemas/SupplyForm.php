<?php

namespace App\Filament\Portal\Resources\Supplies\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

class SupplyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Supply Details')
                ->description('Fill up the form and make sure all details are correct.')
                ->schema([
                TextInput::make('item_name')
                    ->label('Item Name')
                    ->placeholder('Enter item name')
                    ->required()
                    ->unique(),
                
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->suffix('pcs'),

                TextInput::make('location')
                    ->required()
                    ->placeholder('Enter location details'),
                ])->columnSpan(2)->columns(2)->compact(),

                Section::make('Photo Upload')
                ->schema([
                    FileUpload::make('picture')
                    ->label('Item Photo')
                    ->columnSpanFull()
                    ->image()
                    ->imageEditor()
                    
                    //IMG DIRECTORY
                    ->disk('public')
                    ->directory('supply/pictures')
                    ->visibility('public')

                    //FILE SIZE LIMIT
                    ->maxSize(10000),
                ])->columnSpan(1)->columns(2)->compact(),

            ])->columns(3);
    }
}
