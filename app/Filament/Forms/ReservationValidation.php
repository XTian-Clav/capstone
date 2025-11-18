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
    public static function roomDateField(string $field, string $label, int $defaultHour, string $foreignKey, string $reserveModel): DateTimePicker
    {
        return DateTimePicker::make($field)
            ->label($label)
            ->displayFormat('F j, Y — h:i A')
            ->required()
            ->native(false)
            ->seconds(false)
            ->reactive()
            ->default(fn () => Carbon::now()->setTime($defaultHour, $field === 'end_date' ? 59 : 0))
            ->rule(function ($get) use ($field, $foreignKey, $reserveModel) {
                return function (string $attribute, $value, $fail) use ($get, $field, $foreignKey, $reserveModel) {
                    $date = Carbon::parse($value);
                    $otherField = $field === 'start_date' ? 'end_date' : 'start_date';
                    $otherDate = $get($otherField) ? Carbon::parse($get($otherField)) : null;

                    // Start must be before end
                    if ($otherDate) {
                        if ($field === 'start_date' && $date->greaterThanOrEqualTo($otherDate)) {
                            $fail('[Invalid] Start time cannot be after or equal to end time.');
                        }
                        if ($field === 'end_date' && $date->lessThanOrEqualTo($otherDate)) {
                            $fail('[Invalid] End time cannot be before or equal to start time.');
                        }
                    }

                    // Optional overlap check
                    $foreignId = $get($foreignKey);
                    if ($foreignId && $get('status') !== 'Rejected' && $otherDate) {
                        $overlap = $reserveModel::query()
                            ->where('status', 'Approved')
                            ->where($foreignKey, $foreignId)
                            ->when($get('id'), fn($q) => $q->where('id', '!=', $get('id')))
                            ->where(function ($q) use ($date, $otherDate, $field) {
                                $start = $field === 'start_date' ? $date : $otherDate;
                                $end = $field === 'end_date' ? $date : $otherDate;

                                $q->where('start_date', '<', $end)
                                  ->where('end_date', '>', $start);
                            })
                            ->exists();

                        if ($overlap) {
                            $fail('Someone already reserved in this schedule. Please select another time.');
                        }
                    }
                };
            });
    }

    public static function roomStartDate(string $foreignKey, string $reserveModel): DateTimePicker
    {
        return self::roomDateField('start_date', 'Start Date', 6, $foreignKey, $reserveModel);
    }

    public static function roomEndDate(string $foreignKey, string $reserveModel): DateTimePicker
    {
        return self::roomDateField('end_date', 'End Date', 11, $foreignKey, $reserveModel);
    }

    public static function dateField(string $field, string $label, int $defaultHour): DateTimePicker
    {
        return DateTimePicker::make($field)
            ->label($label)
            ->displayFormat('F j, Y — h:i A')
            ->required()
            ->native(false)
            ->seconds(false)
            ->reactive() // make the field reactive
            ->default(fn () => Carbon::now()->setTime($defaultHour, 0))
            ->rule(function ($get) use ($field) {
                return function (string $attribute, $value, $fail) use ($get, $field) {
                    $date = Carbon::parse($value);
                    $otherField = $field === 'start_date' ? 'end_date' : 'start_date';
                    $otherDate = $get($otherField) ? Carbon::parse($get($otherField)) : null;

                    // Business hours: 8AM–6PM
                    if ($date->hour < 8 || $date->hour > 18) {
                        $fail('Reservations are allowed only between 8:00 AM and 6:00 PM.');
                    }

                    if ($otherDate) {
                        if ($field === 'start_date' && $date->greaterThanOrEqualTo($otherDate)) {
                            $fail('[Invalid] Start time cannot be after or equal to end time.');
                        }
                        if ($field === 'end_date' && $date->lessThanOrEqualTo($otherDate)) {
                            $fail('[Invalid] End time cannot be before or equal to start time.');
                        }
                    }
                };
            });
    }

    public static function startDate(): DateTimePicker
    {
        return self::dateField('start_date', 'Start Date', 8);
    }

    public static function endDate(): DateTimePicker
    {
        return self::dateField('end_date', 'End Date', 18);
    }

    protected static function getAvailableItem(string $type, int $itemId, string $startDate, string $endDate, ?int $excludeReservationId = null): int
    {
        $modelClass = $type === 'supply' ? Supply::class : Equipment::class;
        $reserveClass = $type === 'supply' ? ReserveSupply::class : ReserveEquipment::class;
        $idField = "{$type}_id";

        $item = $modelClass::find($itemId);
        if (! $item) return 0;

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $reservedQty = $reserveClass::query()
            ->where($idField, $item->id)
            ->where('status', 'Approved')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end])
                  ->orWhere(fn($q2) => $q2->where('start_date', '<=', $start)
                                           ->where('end_date', '>=', $end));
            })
            ->when($excludeReservationId, fn($q) => $q->where('id', '!=', $excludeReservationId))
            ->sum('quantity');

        return max(0, $item->quantity - $reservedQty);
    }

    public static function supplyQuantity(): TextInput
    {
        return self::itemQuantity('supply');
    }

    public static function equipmentQuantity(): TextInput
    {
        return self::itemQuantity('equipment');
    }

    protected static function itemQuantity(string $type): TextInput
    {
        $idField = $type === 'supply' ? 'supply_id' : 'equipment_id';
        $nameField = $type === 'supply' ? 'item_name' : 'equipment_name';

        return TextInput::make('quantity')
            ->numeric()
            ->default(1)
            ->minValue(1)
            ->suffix('pcs')
            ->required()
            ->reactive()
            ->helperText(function ($get, $record) use ($type, $idField, $nameField) {
                $itemId = $get($idField);
                $start = $get('start_date');
                $end = $get('end_date');

                if (! $itemId || ! $start || ! $end) return 'No selected item';

                $available = self::getAvailableItem($type, $itemId, $start, $end, $record?->id);
                $model = $type === 'supply' ? Supply::find($itemId) : Equipment::find($itemId);

                return $model ? "Available for {$model->{$nameField}}: {$available} pcs" : "Available: {$available} pcs";
            })
            ->rule(function ($get, $record) use ($type, $idField, $nameField) {
                return function (string $attribute, $value, $fail) use ($get, $record, $type, $idField, $nameField) {
                    $itemId = $get($idField);
                    $start = $get('start_date');
                    $end = $get('end_date');

                    if (! $itemId || ! $start || ! $end) return;

                    $available = self::getAvailableItem($type, $itemId, $start, $end, $record?->id);
                    $model = $type === 'supply' ? Supply::find($itemId) : Equipment::find($itemId);

                    if ($get('status') !== 'Rejected') {
                        if ($available <= 0) {
                            $fail("{$model?->{$nameField}} is currently out of stock.");
                        } elseif ($value > $available) {
                            $fail("Only {$available} pcs are available for the selected date.");
                        }
                    }
                };
            });
    }
}