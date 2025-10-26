<?php

namespace App\Filament\Forms;

use Carbon\Carbon;
use App\Models\Supply;
use App\Models\Equipment;
use App\Models\ReserveSupply;
use App\Models\ReserveEquipment;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;

class ReservationValidation
{
    public static function startDate(string $foreignKey, string $reserveModel): DateTimePicker
    {
        return DateTimePicker::make('start_date')
            ->label('Start Date')
            ->displayFormat('F j, Y — h:i A')
            ->required()
            ->native(false)
            ->seconds(false)
            ->rule(function ($get) use ($foreignKey, $reserveModel) {
                return function (string $attribute, $value, $fail) use ($get, $foreignKey, $reserveModel) {
                    $time = Carbon::parse($value);
                    $hour = $time->format('H');

                    // business hours rule
                    if ($hour < 8 || $hour >= 18) {
                        $fail('Reservations are allowed only between 8:00 AM and 6:00 PM.');
                    }

                    // overlap validation (optional, only if model supports it)
                    if ($get('end_date') && $get($foreignKey)) {
                        $start = Carbon::parse($value);
                        $end = Carbon::parse($get('end_date'));

                        $overlap = $reserveModel::query()
                            ->where('status', 'Approved')
                            ->where($foreignKey, $get($foreignKey))
                            ->when($get('id'), fn ($q) => $q->where('id', '!=', $get('id')))
                            ->where(function ($q) use ($start, $end) {
                                $q->where('start_date', '<', $end)
                                  ->where('end_date', '>', $start);
                            })
                            ->exists();

                        if ($overlap) {
                            $fail('The selected item is already reserved in this time range.');
                        }
                    }
                };
            })
            ->default(fn () => Carbon::now()->setHour(8)->setMinute(0)->setSecond(0));
    }

    public static function endDate(string $foreignKey, string $reserveModel): DateTimePicker
    {
        return DateTimePicker::make('end_date')
            ->label('End Date')
            ->displayFormat('F j, Y — h:i A')
            ->required()
            ->native(false)
            ->seconds(false)
            ->rule(function ($get) use ($foreignKey, $reserveModel) {
                return function (string $attribute, $value, $fail) use ($get, $foreignKey, $reserveModel) {
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

                    // overlap validation
                    if ($get('start_date') && $get($foreignKey)) {
                        $start = Carbon::parse($get('start_date'));
                        $overlap = $reserveModel::query()
                            ->where('status', 'Approved')
                            ->where($foreignKey, $get($foreignKey))
                            ->when($get('id'), fn ($q) => $q->where('id', '!=', $get('id')))
                            ->where(function ($q) use ($start, $end) {
                                $q->where('start_date', '<', $end)
                                  ->where('end_date', '>', $start);
                            })
                            ->exists();

                        if ($overlap) {
                            $fail('The selected item is already reserved in this time range.');
                        }
                    }
                };
            })
            ->default(fn () => Carbon::now()->setHour(18)->setMinute(0)->setSecond(0));
    }

    public static function supplyQuantity(): TextInput
    {
        return TextInput::make('quantity')
            ->numeric()
            ->default(1)
            ->minValue(1)
            ->suffix('pcs')
            ->required()
            ->rule(function ($get, $record) {
                return function (string $attribute, $value, $fail) use ($get, $record) {
                    $supply = Supply::find($get('supply_id'));
                    $start = $get('start_date');
                    $end = $get('end_date');

                    if (! $supply || ! $start || ! $end) return;

                    $start = Carbon::parse($start);
                    $end = Carbon::parse($end);

                    if ($supply->quantity <= 0) {
                        $fail("{$supply->supply_name} is currently out of stock.");
                        return;
                    }

                    $reservedQty = ReserveSupply::query()
                        ->where('supply_id', $supply->id)
                        ->where('status', 'Approved')
                        ->whereDate('start_date', '<=', $end->toDateString())
                        ->whereDate('end_date', '>=', $start->toDateString())
                        ->where('start_date', '<=', $end)
                        ->where('end_date', '>=', $start)
                        ->when($record?->exists, fn($q) => $q->where('id', '!=', $record->id))
                        ->sum('quantity');

                    $available = $supply->quantity - $reservedQty;

                    if ($available <= 0)
                        $fail("{$supply->supply_name} is fully reserved for this date.");
                    elseif ($value > $available)
                        $fail("Only {$available} pcs are available for the selected date.");
                };
            });
    }

    public static function equipmentQuantity(): TextInput
    {
        return TextInput::make('quantity')
            ->numeric()
            ->default(1)
            ->minValue(1)
            ->suffix('pcs')
            ->required()
            ->rule(function ($get, $record) {
                return function (string $attribute, $value, $fail) use ($get, $record) {
                    $equipment = Equipment::find($get('equipment_id'));
                    $start = $get('start_date');
                    $end = $get('end_date');

                    if (! $equipment || ! $start || ! $end) return;

                    $start = Carbon::parse($start);
                    $end = Carbon::parse($end);

                    if ($equipment->quantity <= 0) {
                        $fail("{$equipment->equipment_name} is currently out of stock.");
                        return;
                    }

                    $reservedQty = ReserveEquipment::query()
                        ->where('equipment_id', $equipment->id)
                        ->where('status', 'Approved')
                        ->whereDate('start_date', '<=', $end->toDateString())
                        ->whereDate('end_date', '>=', $start->toDateString())
                        ->where('start_date', '<=', $end)
                        ->where('end_date', '>=', $start)
                        ->when($record?->exists, fn($q) => $q->where('id', '!=', $record->id))
                        ->sum('quantity');

                    $available = $equipment->quantity - $reservedQty;

                    if ($available <= 0)
                        $fail("{$equipment->equipment_name} is fully reserved for this date.");
                    elseif ($value > $available)
                        $fail("Only {$available} pcs are available for the selected date.");
                };
            });
    }
}