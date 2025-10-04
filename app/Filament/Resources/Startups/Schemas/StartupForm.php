<?php

namespace App\Filament\Resources\Startups\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\DateTimePicker;

class StartupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('logo') ->label('Startup Logo')
                    ->default(null)
                    ->image()
                    ->disk('public')
                    ->directory('startups/logos')
                    ->imagePreviewHeight('100')
                    ->disablePreview()
                    ->afterStateUpdated(function ($state, $set, $record) {
                        if ($record && $record->logo && $state !== $record->logo) {
                            Storage::disk('public')->delete($record->logo);
                        }
                    }),

                TextInput::make('startup_name')
                    ->required()
                    ->unique(),

                TextInput::make('founder')
                    ->required(),

                DateTimePicker::make('submission_date')
                    ->default(now())
                    ->required()
                    ->withoutSeconds(),

                Select::make('status')
                    ->options(\App\Models\Startup::STATUS)
                    ->default('Pending')
                    ->required(),
            ]);
    }
}
