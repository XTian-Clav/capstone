<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Pages;

use Filament\Resources\Pages\Page;
use App\Filament\Actions\BackButton;
use App\Filament\Portal\Resources\ReserveRooms\ReserveRoomResource;

class PrintFile extends Page
{
    protected static string $resource = ReserveRoomResource::class;

    protected string $view = 'filament.portal.resources.reserve-rooms.pages.print-file';

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
        ];
    }
}
