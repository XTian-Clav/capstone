<?php

namespace App\Filament\Portal\Resources\ReserveEquipment\Pages;

use App\Models\Equipment;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Portal\Resources\ReserveEquipment\ReserveEquipmentResource;

class CreateReserveEquipment extends CreateRecord
{
    protected static string $resource = ReserveEquipmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        $user = auth()->user();
        $equipmentName = Equipment::find($data['equipment_id'] ?? null)?->equipment_name ?? 'a equipment';

        if (! $user->hasAnyRole(['admin', 'super_admin'])) {
            Notification::make()
                ->title('Reservation Submitted')
                ->body('Your reservation for ' . $equipmentName . ' has been submitted successfully.')
                ->sendToDatabase($user);
        }

        return $data;
    }
}
