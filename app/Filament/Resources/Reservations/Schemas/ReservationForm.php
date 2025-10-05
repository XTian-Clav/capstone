<?php

namespace App\Filament\Resources\Reservations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;

class ReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Reservation Form')
                ->description('Fill up the form and make sure all details are correct.')
                ->schema([
                    TextInput::make('borrower')
                        ->required(),
                    
                    TextInput::make('reservation_type')
                        ->required(),
                    
                    TextInput::make('quantity')
                        ->required()
                        ->numeric()
                        ->formatStateUsing(fn ($state) => number_format($state)),

                    TextInput::make('reservation')->columnSpan(2)
                    ->required(),

                    Select::make('purpose')
                        ->options(\App\Models\Reservation::PURPOSE)
                        ->required()
                        ->native(false),

                    DateTimePicker::make('submission_date')
                        ->default(now())
                        ->required()
                        ->withoutSeconds()
                        ->native(false),

                    Select::make('status')
                        ->options(\App\Models\Reservation::STATUS)
                        ->default('Pending')
                        ->required()
                        ->native(false),
                ])->columns(3),
            ])->columns(1);
    }
}
