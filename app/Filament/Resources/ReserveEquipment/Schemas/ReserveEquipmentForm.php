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
use Carbon\Carbon;

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
                        ->displayFormat('F j, Y — h:i A')
                        ->required()
                        ->native(false)
                        ->seconds(false)
                        ->rule(function () {
                            return function (string $attribute, $value, $fail) {
                                $time = Carbon::parse($value); // ✅ convert to Carbon
                                $hour = $time->format('H');
                    
                                if ($hour < 8 || $hour >= 18) {
                                    $fail('Reservations are allowed only between 8:00 AM and 6:00 PM.');
                                }
                            };
                        }),

                    DateTimePicker::make('end_date')
                        ->label('End Date & Time')
                        ->displayFormat('F j, Y — h:i A')
                        ->required()
                        ->native(false)
                        ->seconds(false)
                        ->rule(function ($get) {
                            return function (string $attribute, $value, $fail) use ($get) {
                                $end = Carbon::parse($value); // convert to Carbon
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
                            };
                        }),
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
                        ->rule(function ($get, $record) {
                            return function (string $attribute, $value, $fail) use ($get, $record) {
                                $equipment = Equipment::find($get('equipment_id'));
                                if (! $equipment) return;
                    
                                // Get overlapping APPROVED reservations only
                                $reservedQty = ReserveEquipment::query()
                                    ->whereHas('equipment', fn ($q) => $q->where('equipment_id', $equipment->id))
                                    ->where('status', 'Approved')
                                    ->where(function ($q) use ($get, $record) {
                                        $start = $get('start_date');
                                        $end = $get('end_date');
                    
                                        // Overlapping time logic:
                                        //  (A.start <= B.end) && (A.end >= B.start)
                                        $q->where(function ($inner) use ($start, $end) {
                                            $inner->where('start_date', '<=', $end)
                                                  ->where('end_date', '>=', $start);
                                        });
                    
                                        // Exclude current record when editing
                                        if ($record?->exists) {
                                            $q->where('id', '!=', $record->id);
                                        }
                                    })
                                    ->sum('quantity');
                    
                                // Compute available
                                $available = $equipment->quantity - $reservedQty;
                    
                                if ($available <= 0) {
                                    $fail("{$equipment->equipment_name} is fully reserved for this time slot.");
                                } elseif ($value > $available) {
                                    $fail("Only {$available} pcs are available for the selected period.");
                                }
                            };
                        }),                    
                ])->compact(),
            ])->columns(3);
    }
}
