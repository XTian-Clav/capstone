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
use App\Filament\Forms\ReservationValidation;
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
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->name : null)
                            ->required(),

                        TextInput::make('company')
                            ->label('Company / Office')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->company : null)
                            ->required(),
                        
                        TextInput::make('contact')
                            ->label('Contact')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->contact : null)
                            ->required(),
                        
                        TextInput::make('email')
                            ->label('Email')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->email : null)
                            ->required(),
                    ])->columnSpan(2)->columns(2)->compact()->secondary(),

                    Section::make()
                    ->schema([
                        ReservationValidation::startDate('room_id', ReserveRoom::class),
                        ReservationValidation::endDate('room_id', ReserveRoom::class),
                        Text::make('Please adjust the time if needed. Reservations are generally from 8:00 AM to 6:00 PM.')->columnSpanFull(),
                    ])->columnSpan(2)->columns(2)->compact()->secondary(),
                    
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
                    ])->columnSpanFull()->compact()->secondary(),

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
                        ->native(false)
                        ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin'])),                  
                ])->compact(),
            ])->columns(3);
    }
}