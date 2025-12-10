<?php

namespace App\Filament\Portal\Resources\ReserveRooms\Pages;

use App\Models\ReserveRoom;
use Filament\Resources\Pages\Page;
use App\Filament\Actions\BackButton;
use App\Filament\Actions\Print\PrintRoomAction;
use App\Filament\Portal\Resources\ReserveRooms\ReserveRoomResource;

class PrintRoom extends Page
{
    protected static string $resource = ReserveRoomResource::class;

    protected string $view = 'filament.portal.resources.reserve-rooms.pages.print-room';

    public $record;
    public $reserveRoom;

    public function mount($record)
    {
        $this->record = $record;
        $this->reserveRoom = ReserveRoom::with('room')->find($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
            PrintRoomAction::make(),
        ];
    }
}
