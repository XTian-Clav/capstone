<?php

namespace App\Filament\Portal\Resources\Rooms\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Size;
use App\Filament\Actions\BackButton;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Actions\Room\CloseRoomSchedule;
use App\Filament\Portal\Resources\Rooms\RoomResource;
use App\Filament\Portal\Resources\ReserveRooms\ReserveRoomResource;

class ViewRoom extends ViewRecord
{
    protected static string $resource = RoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CloseRoomSchedule::make()->visible(fn () => auth()->user()->hasRole('incubatee')),
            BackButton::make()->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),
            EditAction::make()->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),
        ];
    }
}
