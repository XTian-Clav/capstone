<?php

namespace App\Filament\Portal\Resources\ReserveEquipment\Schemas;

use Carbon\Carbon;
use App\Models\Equipment;
use Filament\Schemas\Schema;
use App\Models\ReserveEquipment;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Text;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use App\Filament\Actions\EquipmentTerms;
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
                            ->readOnly(fn ($record, $context) => $context === 'edit')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->name : null)
                            ->required(),

                        TextInput::make('company')
                            ->label('Office')
                            ->placeholder('enter office or company name')
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
                            ->placeholder('enter reserver email')
                            ->readOnly(fn ($record, $context) => $context === 'edit')
                            ->default(fn () => auth()->user()?->hasRole('incubatee') ? auth()->user()?->email : null)
                            ->required(),
                    ])->columnSpan(2)->columns(2)->compact(),

                    Section::make()
                    ->schema([
                        ReservationValidation::startDate('equipment_id', ReserveEquipment::class),
                        ReservationValidation::endDate('equipment_id', ReserveEquipment::class),
                        Text::make('Please adjust the time if needed. Reservations are generally from 8:00 AM to 6:00 PM.')->columnSpanFull(),
                    ])->columnSpan(2)->columns(2)->compact(),
                ])->columnSpan(2)->columns(2)->compact(),

                Grid::make()
                ->schema([
                    Section::make('Select Equipment')
                    ->schema([
                        Select::make('equipment_id')
                            ->hiddenLabel()
                            ->placeholder('Select equipment')
                            ->options(Equipment::where('quantity', '>', 0)->pluck('equipment_name', 'id'))
                            ->disabled(fn ($record, $context) => $context === 'edit')
                            ->searchable()
                            ->required()
                            ->reactive(),
                        
                        Placeholder::make('equipment_details')
                            ->hiddenLabel()
                            ->content(function ($get) {
                                $equipment = Equipment::find($get('equipment_id'));
                                if (! $equipment) return '';
                                $html = '';

                                // Equipment preview image
                                if ($equipment->picture) {
                                    $url = Storage::url($equipment->picture);
                                    $html .= "<div style='max-width:400px;aspect-ratio:16/9;margin-bottom:0.5rem;'>
                                                <img src='{$url}' style='width:100%;height:100%;object-fit:cover;border-radius:0.5rem;'>
                                            </div>";
                                }

                                $html .= "<div style='margin-top:0.5rem; font-weight:600;'>
                                            Available: {$equipment->quantity} pcs
                                        </div>";

                                return $html;
                            })
                            ->reactive()
                            ->html(),

                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->suffix('pcs')
                            ->required()
                            ->rule(function ($get, $record) {
                                return function ($attribute, $value, $fail) use ($get, $record) {
                                    $equipment = Equipment::find($get('equipment_id'));
                                    if (! $equipment) return;
                        
                                    $available = $equipment->quantity;

                                    if ($record?->status === 'Approved') {
                                        $available += $record->quantity;
                                    }
                        
                                    if ($value > $available) {
                                        $fail("Only {$available} pcs are available for the selected equipment.");
                                    }
                                };
                            }),
                    ])->columnSpanFull()->compact(),

                    Section::make()
                    ->schema([
                        EquipmentTerms::make(),
                    ])->columnSpanFull()->compact(),
                ])->columnSpan(1),

                Section::make('Admin Review')
                ->schema([
                    Select::make('status')
                        ->options(ReserveEquipment::STATUS)
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
