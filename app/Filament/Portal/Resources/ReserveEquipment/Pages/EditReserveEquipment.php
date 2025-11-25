<?php

namespace App\Filament\Portal\Resources\ReserveEquipment\Pages;

use App\Models\User;
use App\Models\Equipment;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Portal\Resources\ReserveEquipment\ReserveEquipmentResource;
use App\Filament\Portal\Resources\ReserveEquipment\Pages\EditReserveEquipment;
use App\Filament\Portal\Resources\ReserveEquipment\Pages\ViewReserveEquipment;

class EditReserveEquipment extends EditRecord
{
    protected static string $resource = ReserveEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
