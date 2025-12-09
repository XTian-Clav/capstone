<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Schemas;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\ReserveRoom;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Text;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use App\Filament\Actions\Room\RoomTerms;
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
                            ->readOnly(fn ($record, $context) => $context === 'edit')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->name : null)
                            ->required(),

                        TextInput::make('company')
                            ->label('Company / Office')
                            ->placeholder('Enter office or company name')
                            ->readOnly(fn ($record, $context) => $context === 'edit')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->company : null)
                            ->required(),
                        
                        TextInput::make('contact')
                            ->label('Contact')
                            ->mask('0999-999-9999')
                            ->placeholder('09XX-XXX-XXXX')
                            ->readOnly(fn ($record, $context) => $context === 'edit')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->contact : null)
                            ->required(),
                        
                        TextInput::make('email')
                            ->label('Email')
                            ->placeholder('Enter reserver email')
                            ->readOnly(fn ($record, $context) => $context === 'edit')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->email : null)
                            ->required(),
                    ])->columnSpan(2)->columns(2)->compact(),

                    Section::make()
                    ->schema([
                        ReservationValidation::roomStartDate('room_id', ReserveRoom::class),
                        ReservationValidation::roomEndDate('room_id', ReserveRoom::class),
                        Text::make('Overnight schedules are allowed for Room reservations.')->columnSpanFull(),
                    ])->columnSpan(2)->columns(2)->compact(),
                ])->columnSpan(2)->columns(2)->compact(),

                Grid::make()
                ->schema([
                    Section::make('Select Room')
                    ->schema([
                        Select::make('room_id')
                            ->hiddenLabel()
                            ->placeholder('Select room')
                            ->options(fn() => Room::where('is_available', true)->pluck('room_type', 'id'))
                            ->disabled(fn ($record, $context) => $context === 'edit')
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
                    ])->columnSpanFull()->compact(),
                    
                    Section::make()
                    ->schema([
                        RoomTerms::make(),
                    ])->columnSpanFull()->compact(),
                ])->columnSpan(1),

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