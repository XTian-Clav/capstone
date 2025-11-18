<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;

class ScheduleForm
{
    public static function scheduleRepeater(): Repeater
    {
        // Generate times: 1 AM to 12 PM
        $times = [];
        foreach (['AM', 'PM'] as $meridiem) {
            for ($i = 1; $i <= 12; $i++) {
                $times["$i $meridiem"] = "$i $meridiem";
            }
        }

        return Repeater::make('schedules')
            ->label('Schedules')
            ->schema([
                Select::make('day')
                    ->label('Day')
                    ->options([
                        'Monday' => 'Monday',
                        'Tuesday' => 'Tuesday',
                        'Wednesday' => 'Wednesday',
                        'Thursday' => 'Thursday',
                        'Friday' => 'Friday',
                        'Saturday' => 'Saturday',
                        'Sunday' => 'Sunday',
                    ])
                    ->required()
                    ->searchable()
                    ->placeholder('Choose day'),

                Select::make('start_time')
                    ->label('Start Time')
                    ->options($times)
                    ->required()
                    ->searchable()
                    ->placeholder('Start Time'),

                Select::make('end_time')
                    ->label('End Time')
                    ->options($times)
                    ->required()
                    ->searchable()
                    ->placeholder('End Time'),
            ])
            ->columns(3)
            ->defaultItems(1)
            ->disableItemMovement()
            ->addActionLabel('Add another schedule');
    }
}