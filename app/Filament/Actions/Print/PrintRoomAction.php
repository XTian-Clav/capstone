<?php

namespace App\Filament\Actions\Print;

use Filament\Actions\Action;
use App\Filament\Portal\Resources\ReserveRooms\Pages\PrintRoom;

class PrintRoomAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'back')
            ->color('secondary')
            ->label('Print')
            ->icon('heroicon-s-document-text')
            ->url(fn ($record) => PrintRoom::getUrl(['record' => $record->id]));
    }
}
