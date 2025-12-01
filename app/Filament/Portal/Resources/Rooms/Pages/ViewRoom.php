<?php

namespace App\Filament\Portal\Resources\Rooms\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use App\Filament\Actions\BackButton;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Portal\Resources\Rooms\RoomResource;

class ViewRoom extends ViewRecord
{
    protected static string $resource = RoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            BackButton::make(),
            EditAction::make(),
        ];
    }
}
