<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Schemas;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\ReserveRoom;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Text;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
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
                            ->placeholder('Enter reserver name')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->name : null)
                            ->required(),

                        TextInput::make('company')
                            ->label('Company / Office')
                            ->placeholder('Enter office or company name')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->company : null)
                            ->required(),
                        
                        TextInput::make('contact')
                            ->label('Contact')
                            ->mask('0999-999-9999')
                            ->placeholder('09XX-XXX-XXXX')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->contact : null)
                            ->required(),
                        
                        TextInput::make('email')
                            ->label('Email')
                            ->placeholder('Enter reserver email')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->email : null)
                            ->required(),
                    ])->columnSpan(2)->columns(2)->compact()->secondary(),

                    Section::make()
                    ->schema([
                        ReservationValidation::roomStartDate('room_id', ReserveRoom::class),
                        ReservationValidation::roomEndDate('room_id', ReserveRoom::class),
                        Text::make('Overnight schedules are allowed for Room reservations.')->columnSpanFull(),
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
                    Select::make('room_id')
                        ->hiddenLabel()
                        ->placeholder('Select room')
                        ->options(fn() => Room::where('is_available', true)
                                ->pluck('room_type', 'id'))
                        ->searchable()
                        ->required()
                        ->reactive(),
                    
                    Placeholder::make('room_details')
                        ->hiddenLabel()
                        ->content(function ($get) {
                            $room = Room::find($get('room_id'));
                            if (! $room) return '';
                            $html = '';

                            // Room preview image
                            if ($room->picture) {
                                $url = Storage::url($room->picture);
                                $html .= "<div style='max-width:400px;aspect-ratio:16/9;margin-bottom:0.5rem;'>
                                            <img src='{$url}' style='width:100%;height:100%;object-fit:cover;border-radius:0.5rem;'>
                                        </div>";
                            }
                            // Room capacity
                            $html .= "<div style='display:inline-block;background-color:#013267;color:white;padding:0.20rem 0.5rem;border-radius:0.5rem;'>
                                        Capacity: {$room->capacity}
                                    </div>";

                            // Room inclusions
                            if ($room->inclusions) {
                                $html .= "<div style='margin-top:0.5rem;'>Inclusions: {$room->inclusions}</div>";
                            }

                            return $html;
                        })
                        ->reactive()
                        ->html(),
                ])->compact(),
                
                Section::make('Admin Review')
                ->schema([
                    Select::make('status')
                        ->options(ReserveRoom::STATUS)
                        ->default('Pending')
                        ->required()
                        ->native(false)
                        ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin'])), 
                    Textarea::make('admin_comment')
                        ->columnSpanFull()
                        ->nullable()
                        ->rows(4),
                ])->columnSpan(2)->columns(2)->compact()->visible(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
            ])->columns(3);
    }
}