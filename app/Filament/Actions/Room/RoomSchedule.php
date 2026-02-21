<?php

namespace App\Filament\Actions\Room;

use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use App\Filament\Portal\Pages\RoomSchedule as RoomSchedulePage;

class RoomSchedule
{
    public static function make(): Action
    {
        return Action::make('view_schedules')
            ->button()
            ->outlined()
            ->color('success')
            ->size(Size::Small)
            ->label('View Schedules')
            ->icon('heroicon-o-calendar-days')
            ->url(fn (): string => RoomSchedulePage::getUrl())
            ->openUrlInNewTab();
    }
}