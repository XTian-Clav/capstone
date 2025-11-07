<?php

namespace App\Filament\Portal\Resources\ReserveEquipment\Schemas;

use Carbon\Carbon;
use App\Models\Equipment;
use Filament\Schemas\Schema;
use App\Models\ReserveEquipment;
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

class ReserveEquipmentForm
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
                            ->label('Office')
                            ->placeholder('enter office or company name')
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
                            ->placeholder('enter reserver email')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->email : null)
                            ->required(),
                    ])->columnSpan(2)->columns(2)->compact()->secondary(),

                    Section::make()
                    ->schema([
                        ReservationValidation::startDate('equipment_id', ReserveEquipment::class),
                        ReservationValidation::endDate('equipment_id', ReserveEquipment::class),
                        Text::make('Please adjust the time if needed. Reservations are generally from 8:00 AM to 6:00 PM.')->columnSpanFull(),
                    ])->columnSpan(2)->columns(2)->compact()->secondary(),
                    
                    Section::make()
                    ->collapsed()->description('Terms and Conditions')
                    ->schema([
                        Text::make(new HtmlString('
                            <div style="text-align: justify; font-size: 0.85rem; line-height: 1.5; font-family: monospace;">
                                1. I agree to promptly return the equipment borrowed.<br><br>
                                2. I agree to pay for any damage or loss of the equipment during the time when the equipment is in my possession.<br><br>
                                3. I pledge that the equipment I borrowed from PITBI/PSU will be used solely for the official purpose stated above and not for personal purposes.
                            </div>
                        ')),

                        Checkbox::make('accept_terms')
                        ->label('I agree to the Terms and Conditions')
                        ->required()
                        ->rules(['accepted'])
                        ->columnSpan('full'),
                    ])->columnSpanFull()->compact()->secondary(),

                ])->columnSpan(2)->columns(2)->compact(),

                Section::make('Select Equipment')
                ->schema([
                    Select::make('equipment_id')
                        ->hiddenLabel()
                        ->placeholder('Select equipment')
                        ->options(Equipment::where('quantity', '>', 0)
                                ->pluck('equipment_name', 'id')
                        )
                        ->searchable()
                        ->required()
                        ->reactive(),

                    // Photo preview
                    Placeholder::make('equipment_preview')
                        ->hiddenLabel()
                        ->content(function ($get) {
                            $equipment = Equipment::find($get('equipment_id'));
                            if (! $equipment?->picture) return '';
                            $url = Storage::url($equipment->picture);
                            return "<div style='max-width:400px;aspect-ratio:16/9'>
                                        <img src='{$url}' class='w-full h-full object-cover rounded-lg border'>
                                    </div>";
                        })
                        ->reactive()
                        ->html(),

                    ReservationValidation::equipmentQuantity(),

                    Select::make('status')
                        ->options(ReserveEquipment::STATUS)
                        ->default('Pending') 
                        ->required()
                        ->native(false)
                        ->disabled(fn () => ! auth()->user()->hasAnyRole(['admin', 'super_admin'])),    
                ])->compact(),
            ])->columns(3);
    }
}
