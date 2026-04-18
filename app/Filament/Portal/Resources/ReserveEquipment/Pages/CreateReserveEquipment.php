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
        
        $equipment = Equipment::find($data['equipment_id'] ?? null);
        $name = $equipment?->equipment_name ?? 'an equipment';
        $qty = $data['quantity'] ?? 1;
        
        $equipmentName = "<strong>{$name} - Qty: {$qty}</strong>";

        if (! $user->hasAnyRole(['admin', 'super_admin'])) {
            Notification::make()
                ->color('warning')
                ->iconColor('warning')
                ->icon('heroicon-o-clock')
                ->title('Equipment Reservation Submitted')
                ->body('Your reservation for ' . $equipmentName . ' has been submitted successfully.')
                ->sendToDatabase($user);
        }

        return $data;
    }
}
