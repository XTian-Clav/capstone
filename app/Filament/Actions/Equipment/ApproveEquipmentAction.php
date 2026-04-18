<?php

namespace App\Filament\Actions\Equipment;

use Filament\Actions\Action;
use App\Models\ReserveEquipment;
use Filament\Support\Enums\Size;
use App\Notifications\EquipmentApproved;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\ReserveEquipment\Pages\ViewReserveEquipment;

class ApproveEquipmentAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'approve')
            ->button()
            ->label('Approve')
            ->color('success')
            ->icon('heroicon-m-check-circle')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Approve ' . ($action->getRecord()?->equipment?->equipment_name ?? 'Reservation'))
            ->modalDescription('Are you sure you want to approve this reservation?')
            ->modalSubmitActionLabel('Approve')
            ->action(function (ReserveEquipment $record) {
                $equipment = $record->equipment;
                if (! $equipment) {
                    Notification::make()
                        ->title('Cannot Approve')
                        ->body("This reservation has no associated equipment.")
                        ->danger()
                        ->send();
                    return;
                }

                if ($equipment->quantity < $record->quantity) {
                    Notification::make()
                        ->title('Cannot Approve')
                        ->body("Not enough stock for {$equipment->equipment_name}.")
                        ->danger()
                        ->send();
                    return;
                }

                $record->status = 'Approved';
                $record->save();

                $owner = $record->user;
                $admin = auth()->user();

                $name = $record->equipment?->equipment_name ?? 'an equipment';
                $qty = $record->quantity ?? 1;
                $equipmentName = "{$name} - Qty: {$qty}";

                if ($owner) {
                    $owner->notify(new EquipmentApproved($record));
                }

                Notification::make()
                    ->color('success')
                    ->iconColor('sucess')
                    ->icon('heroicon-m-check-circle')
                    ->title('Equipment Reservation Approved')
                    ->body("You approved the reservation for <strong>{$equipmentName}</strong> for " . ($owner?->name ?? 'Unknown user') . ".")
                    ->sendToDatabase($admin);
            })
            ->visible(fn ($record) =>
                $record?->status === 'Pending' &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}
