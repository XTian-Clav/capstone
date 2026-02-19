<?php

namespace App\Filament\Actions\Room;

use App\Models\Room;
use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Section;
use App\Filament\Portal\Resources\Rooms\RoomResource;

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
            ->icon('heroicon-o-calendar-days');
    }
}
