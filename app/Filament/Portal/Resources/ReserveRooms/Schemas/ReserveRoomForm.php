<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Schemas;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\ReserveRoom;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Text;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\DateTimePicker;

class ReserveRoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Reservation Form')
                ->schema([
                    Section::make()
                    ->schema([
                        TextInput::make('reserved_by')
                            ->label('Reserved By')
                            ->default(fn () => auth()->user()?->name)
                            ->required(),

                        TextInput::make('office')
                            ->label('Office')
                            ->required(),
                        
                        TextInput::make('contact')
                            ->label('Contact')
                            ->required(),
                        
                        TextInput::make('email')
                            ->label('Email')
                            ->default(fn () => auth()->user()?->email)
                            ->required(),
                    ])->columnSpan(2)->columns(2)->compact(),

                    Section::make()
                    ->schema([
                        DateTimePicker::make('start_date')
                            ->label('Start of Rent')
                            ->displayFormat('F j, Y — h:i A')
                            ->required()
                            ->native(false)
                            ->seconds(false)
                            ->rule(function ($get) {
                                return function (string $attribute, $value, $fail) use ($get) {
                                    $time = Carbon::parse($value);
                                    $hour = $time->format('H');
                
                                    if ($hour < 8 || $hour >= 18) {
                                        $fail('Reservations are allowed only between 8:00 AM and 6:00 PM.');
                                    }
                
                                    // Overlap validation for approved reservations
                                    if ($get('end_date') && $get('room_id')) {
                                        $start = Carbon::parse($value);
                                        $end = Carbon::parse($get('end_date'));
                
                                        $overlap = ReserveRoom::where('status', 'Approved')
                                            ->where('room_id', $get('room_id'))
                                            ->where('id', '!=', $get('id') ?? 0)
                                            ->where(function ($q) use ($start, $end) {
                                                $q->where('start_date', '<', $end)
                                                  ->where('end_date', '>', $start);
                                            })
                                            ->exists();
                
                                        if ($overlap) {
                                            $fail('The room is already reserved in the selected time range.');
                                        }
                                    }
                                };
                            })
                            ->default(fn () => Carbon::now()->setHour(8)->setMinute(0)->setSecond(0)),

                        DateTimePicker::make('end_date')
                            ->label('End of Rent')
                            ->displayFormat('F j, Y — h:i A')
                            ->required()
                            ->native(false)
                            ->seconds(false)
                            ->rule(function ($get) {
                                return function (string $attribute, $value, $fail) use ($get) {
                                    $end = Carbon::parse($value);
                                    $hour = $end->format('H');
                
                                    if ($hour < 8 || $hour > 18) {
                                        $fail('Reservations are allowed only between 8:00 AM and 6:00 PM.');
                                    }
                
                                    if ($get('start_date')) {
                                        $start = Carbon::parse($get('start_date'));
                                        if ($end->lessThanOrEqualTo($start)) {
                                            $fail('[Invalid] End time is earlier than the start time.');
                                        }
                                    }
                
                                    // Overlap validation for approved reservations
                                    if ($get('start_date') && $get('room_id')) {
                                        $start = Carbon::parse($get('start_date'));
                                        $overlap = ReserveRoom::where('status', 'Approved')
                                            ->where('room_id', $get('room_id'))
                                            ->where('id', '!=', $get('id') ?? 0)
                                            ->where(function ($q) use ($start, $end) {
                                                $q->where('start_date', '<', $end)
                                                  ->where('end_date', '>', $start);
                                            })
                                            ->exists();
                
                                        if ($overlap) {
                                            $fail('The room is already reserved in the selected time range.');
                                        }
                                    }
                                };
                            })
                            ->default(fn () => Carbon::now()->setHour(18)->setMinute(0)->setSecond(0)),
                        Text::make('Please adjust the time if needed. Reservations are generally from 8:00 AM to 6:00 PM.')->columnSpanFull(),
                    ])->columnSpan(2)->columns(2)->compact(),
                    
                Section::make()
                    ->collapsed()->description('Guidelines')
                    ->schema([
                        Text::make(new HtmlString('
                            <div style="text-align: justify; font-size: 0.85rem; line-height: 1.5; font-family: monospace;">
                                ● Follow PITBI’s operating hours.<br>
                                ● Turn off all electronics and lights before leaving.<br>
                                ● Keep the space clean and dispose of trash properly.<br>
                                ● Respect shared spaces and other occupants.<br>
                                ● Securely lock doors and report any security concerns.<br>
                                ● Complete a Client Satisfaction Measurement after the event.<br><br>
                                PITBI reserves the right to approve or deny reservations based on availability and compliance.
                            </div>
                        ')),

                        Checkbox::make('accept_terms')
                        ->label('I agree to abide by these terms.')
                        ->required()
                        ->rules(['accepted'])
                        ->columnSpan('full'),
                    ])->columnSpanFull()->compact(),

                ])->columnSpan(2)->columns(2)->compact(),

                Section::make('Select Room')
                ->schema([
                    Select::make('room_type')
                        ->label('Room Type')
                        ->options(Room::ROOM_TYPE)
                        ->live()
                        ->required()
                        ->native(false)
                        ->afterStateUpdated(fn ($state, $set) => $set('room_id', null)),

                    Select::make('room_id')
                        ->label('Select Room')
                        ->relationship('room', 'room_name', fn ($query, $get) =>
                            $query->where('room_type', $get('room_type'))
                                ->where('is_available', true)
                        )
                        ->preload()
                        ->searchable()
                        ->required()
                        ->reactive()
                        ->native(false),
                    
                    Placeholder::make('room_preview')
                        ->hiddenLabel()
                        ->content(fn ($get) => 
                            ($room = Room::find($get('room_id'))) && $room->picture
                                ? '<div style="max-width:400px;aspect-ratio:16/9">
                                    <img src="' . Storage::url($room->picture) . '?t=' . now()->timestamp . '"
                                </div>'
                                : null
                        )
                        ->reactive()
                        ->html(),

                    Placeholder::make('room_capacity')
                        ->content(fn ($get) => 
                            ($room = Room::find($get('room_id')))
                                ? 'Capacity: ' . $room->capacity: ''
                        )
                        ->weight('semibold')
                        ->color('success')
                        ->reactive()
                        ->hiddenLabel(),

                    Select::make('status')
                        ->options(ReserveRoom::STATUS)
                        ->default('Pending')
                        ->required()
                        ->native(false),                  
                ])->compact(),
            ])->columns(3);
    }
}