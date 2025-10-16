<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Schemas;

use App\Models\Room;
use App\Models\ReserveRoom;
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
                        ->options([
                            'Small Meeting Room' => 'Small Meeting Room',
                            'Training Room' => 'Training Room',
                            'Co-Working Space' => 'Co-Working Space',
                        ])
                        ->afterStateUpdated(function ($state, callable $set) {
                            $set('room_id', null);
                            $set('start_date', Carbon::now()->setHour(8)->setMinute(0)->setSecond(0));
                            $set('end_date', Carbon::now()->setHour(18)->setMinute(0)->setSecond(0));
                            $set('status', 'Pending');
                        })
                        ->reactive()
                        ->required()
                        ->native(false),

                    Select::make('room_id')
                        ->label('Room Name')
                        ->options(fn ($get) => 
                            $get('room_type')
                                ? Room::where('is_available', true)
                                    ->where('room_type', $get('room_type'))
                                    ->pluck('room_name', 'id')
                                : []
                        )
                        ->reactive()
                        ->required()
                        ->native(false),
                    
                    Placeholder::make('room_preview')
                        ->hiddenLabel()
                        ->content(function ($get) {
                            $room = Room::find($get('room_id'));
                            if (!$room || !$room->picture) {
                                return '';
                            }
            
                            // Add timestamp to force refresh when selecting another room
                            $url = Storage::url($room->picture) . '?t=' . now()->timestamp;
            
                            return <<<HTML
                                <div style="max-width:400px;aspect-ratio:16/9">
                                    <img src="{$url}" 
                                        class="w-full h-full object-cover rounded-lg border border-gray-300 shadow-sm">
                                </div>
                            HTML;
                        })
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