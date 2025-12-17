<?php

namespace App\Filament\Actions\Print;

use Filament\Actions\Action;
use App\Filament\Portal\Resources\ReserveRooms\Pages\PrintRoom;

class PrintRoomAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'back')
            ->color('info')
            ->label('Print')
            ->icon('heroicon-s-printer');
    }
}
