<?php

namespace App\Filament\Forms;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;

class ScheduleForm
{
    public static function scheduleRepeater(): Repeater
    {
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
                    ->reactive()
                    ->native(false)
                    ->placeholder('Choose day'),

                Select::make('hour')
                    ->label('Hour')
                    ->options(array_combine(range(1, 12), range(1, 12)))
                    ->required()
                    ->reactive()
                    ->native(false)
                    ->placeholder('Choose hour'),

                Select::make('meridiem')
                    ->label('AM/PM')
                    ->options([
                        'am' => 'am',
                        'pm' => 'pm',
                    ])
                    ->required()
                    ->reactive()
                    ->native(false)
                    ->placeholder('Choose AM/PM'),
            ])
            ->columns(3)
            ->defaultItems(1)
            ->disableItemMovement()
            ->addActionLabel('Create another schedule');
    }
}
