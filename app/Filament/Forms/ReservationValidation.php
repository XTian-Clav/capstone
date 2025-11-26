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
    //Room Datefield
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
                    $current = Carbon::parse($value);
                    $otherField = $field === 'start_date' ? 'end_date' : 'start_date';
                    $other = $get($otherField) ? Carbon::parse($get($otherField)) : null;
    
                    // Validate start < end
                    if ($other) {
                        if ($field === 'start_date' && $current->gte($other)) {
                            $fail('Start time must be before end time.');
                        }
                        if ($field === 'end_date' && $current->lte($other)) {
                            $fail('End time must be after start time.');
                        }
                    }
    
                    // Overlap check for Approved reservations
                    $foreignId = $get($foreignKey);
                    if ($foreignId && $get('status') !== 'Rejected' && $other) {
                        $start = $field === 'start_date' ? $current : $other;
                        $end = $field === 'end_date' ? $current : $other;
    
                        $exists = $reserveModel::query()
                            ->where('status', 'Approved')
                            ->where($foreignKey, $foreignId)
                            ->when($get('id'), fn($q) => $q->where('id', '!=', $get('id')))
                            ->where(function ($q) use ($start, $end) {
                                $q->where('start_date', '<', $end)
                                  ->where('end_date', '>', $start);
                            })
                            ->exists();
    
                        if ($exists) {
                            $fail('Someone already reserved this room during this time.');
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

    //Equipments and Supplies Datefield
    public static function dateField(string $field, string $label, int $defaultHour): DateTimePicker
    {
        return DateTimePicker::make($field)
            ->label($label)
            ->displayFormat('F j, Y — h:i A')
            ->required()
            ->native(false)
            ->seconds(false)
            ->default(fn () => Carbon::now()->setTime($defaultHour, 0))
            ->rule(function ($get) use ($field) {
                return function (string $attribute, $value, $fail) use ($get, $field) {
                    $date = Carbon::parse($value);
                    $otherField = $field === 'start_date' ? 'end_date' : 'start_date';
                    $otherDate = $get($otherField) ? Carbon::parse($get($otherField)) : null;

                    // Optional: Business hours 8AM–6PM
                    if ($date->hour < 8 || $date->hour > 18) {
                        $fail('Reservations are allowed only between 8:00 AM and 6:00 PM.');
                    }

                    // Start must be before end
                    if ($otherDate) {
                        if ($field === 'start_date' && $date->greaterThanOrEqualTo($otherDate)) {
                            $fail('Start time must be before end time.');
                        }
                        if ($field === 'end_date' && $date->lessThanOrEqualTo($otherDate)) {
                            $fail('End time must be after start time.');
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
}