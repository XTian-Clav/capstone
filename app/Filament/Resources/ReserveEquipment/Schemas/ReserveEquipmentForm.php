<?php

namespace App\Filament\Resources\ReserveEquipment\Schemas;

use App\Models\Equipment;
use App\Models\ReserveEquipment;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Facades\Storage;

class ReserveEquipmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Reservation Details')
                ->description('Fill out the reservation details below.')
                ->schema([
                    TextInput::make('reserved_by')
                        ->label('Reserved By')
                        ->default(fn () => auth()->user()?->name)
                        ->required(),
                    
                    Select::make('status')
                        ->options(ReserveEquipment::STATUS)
                        ->default('Pending')
                        ->required()
                        ->native(false),

                    DateTimePicker::make('start_date')
                        ->label('Start Date & Time')
                        ->required()
                        ->native(false),

                    DateTimePicker::make('end_date')
                        ->label('End Date & Time')
                        ->required()
                        ->native(false),
                    
                ])->columnSpan(2)->columns(2)->compact(),

                Section::make('Select Equipment')
                ->schema([
                    Select::make('equipment_id')
                        ->hiddenLabel()
                        ->options(Equipment::where('quantity', '>', 0)
                                ->pluck('equipment_name', 'id')
                        )
                        ->searchable()
                        ->required()
                        ->reactive(),

                    // Photo preview
                    Placeholder::make('equipment_preview')
                        ->hiddenLabel()
                        ->content(fn ($get) =>
                            ($equipment = Equipment::find($get('equipment_id'))) && $equipment->picture
                                ? '<div style="max-width:400px;aspect-ratio:16/9">
                                    <img src="' . Storage::url($equipment->picture) . '" 
                                            class="w-full h-full object-cover rounded-lg border">
                                    </div>'
                                : ''
                        )
                        ->reactive()
                        ->html(),

                        TextInput::make('quantity')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->suffix('pcs')
                        ->required()
                        ->rule(function ($get, $record) { // $record = the current reservation being edited
                            return function (string $attribute, $value, $fail) use ($get, $record) {
                                $equipment = Equipment::find($get('equipment_id'));
                                if (! $equipment) return;
                    
                                if ($equipment->quantity <= 0) {
                                    $fail("{$equipment->equipment_name} is currently out of stock.");
                                    return;
                                }

                                $reservedQty = ReserveEquipment::query()
                                    ->whereHas('equipment', fn ($q) => $q->where('equipment_id', $equipment->id))
                                    ->where('status', '!=', 'Rejected');
                    
                                // Exclude the current reservation if editing
                                if ($record?->exists) {
                                    $reservedQty->where('id', '!=', $record->id);
                                }

                                $reservedQty = $reservedQty->sum('quantity');
                                $available = $equipment->quantity - $reservedQty;

                                if ($available <= 0) {
                                    $fail("{$equipment->equipment_name} is fully reserved at the moment.");
                                } elseif ($value > $available) {
                                    $fail("Only {$available} pcs are available for reservation.");
                                }
                            };
                        }),
                ])->compact(),
            ])->columns(3);
    }
}
