<?php

namespace App\Filament\Resources\Startups\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;

class StartupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Startup Details')
                ->description('Fill up the form and make sure all details are correct.')
                ->schema([
                    TextInput::make('startup_name')->columnSpanFull()
                    ->required()
                    ->unique(),

                TextInput::make('founder')->columnSpanFull()
                    ->required(),

                DateTimePicker::make('submission_date')
                    ->default(now())
                    ->required()
                    ->withoutSeconds()
                    ->native(false),

                Select::make('status')
                    ->options(\App\Models\Startup::STATUS)
                    ->default('Pending')
                    ->required()
                    ->native(false),
                ])->columnSpan(2)->columns(2),

                Section::make('Logo Upload')
                ->schema([
                    FileUpload::make('logo') ->label('Startup Logo')
                    ->label('Startup Logo')
                    ->default(null)
                    ->image()
                    ->imageEditor()

                    //IMG DIRECTORY
                    ->disk('public')
                    ->directory('startups/logos')
                    ->visibility('public')

                     //IMAGE CROP (1:1)
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeMode('cover')

                    //FILE SIZE LIMIT
                    ->maxSize(5120),
                ]),
            ])->columns(3);
    }
}
