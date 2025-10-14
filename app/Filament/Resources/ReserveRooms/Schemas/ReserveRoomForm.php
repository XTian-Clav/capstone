<?php

namespace App\Filament\Resources\ReserveRooms\Schemas;

use App\Models\Equipment;
use App\Models\ReserveEquipment;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Text;
use Illuminate\Support\HtmlString;
use Carbon\Carbon;

class ReserveRoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('reserved_by')
                    ->required(),
                TextInput::make('room_id')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required(),
                TextInput::make('office')
                    ->required(),
                TextInput::make('contact')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('start_date')
                    ->required(),
                DateTimePicker::make('end_date')
                    ->required(),
                Toggle::make('accept_terms')
                    ->required(),
            ]);
    }
}
