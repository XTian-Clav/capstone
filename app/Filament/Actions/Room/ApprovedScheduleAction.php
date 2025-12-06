<?php

namespace App\Filament\Actions\Room;

use Filament\Actions\Action;

class ApprovedScheduleAction
{
    public static function make(): Action
    {
        return Action::make('view_approved_schedule')
            ->button()
            ->color('gray')
            ->label('View Schedules')
            ->icon('heroicon-o-calendar-days');
            //->url(fn (): string => SupplyResource::getUrl('index'));
    }
}