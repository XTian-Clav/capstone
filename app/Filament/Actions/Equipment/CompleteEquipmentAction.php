<?php

namespace App\Filament\Actions\Equipment;

use Filament\Actions\Action;
use App\Models\ReserveEquipment;
use Filament\Support\Enums\Size;
use Filament\Notifications\Notification;
use App\Notifications\EquipmentCompleted;
use App\Filament\Portal\Resources\ReserveEquipment\Pages\ViewReserveEquipment;

class CompleteEquipmentAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'complete')
            ->button()
            ->label('Complete')
            ->color('cyan')
            ->icon('heroicon-m-check-badge')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Complete ' . ($action->getRecord()?->equipment?->equipment_name ?? 'Reservation'))
            ->modalDescription('Mark this reservation as completed? Stocks will be returned.')
            ->modalSubmitActionLabel('Complete')
            ->action(function (ReserveEquipment $record) {
                $equipment = $record->equipment;
                if (! $equipment) {
                    Notification::make()
                        ->title('Cannot Complete')
                        ->body("This reservation has no associated equipment.")
                        ->danger()
                        ->send();
                    return;
                }

                $record->status = 'Completed';
                $record->save();

                $owner = $record->user;
                $admin = auth()->user();
                
                $name = $record->equipment?->equipment_name ?? 'an equipment';
                $qty = $record->quantity ?? 1;
                $equipmentName = "{$name} ({$qty})";

                if ($owner) {
                    $owner->notify(new EquipmentCompleted($record));
                }

                Notification::make()
                    ->color('cyan')
                    ->iconColor('cyan')
                    ->icon('heroicon-m-check-badge')
                    ->title('Equipment Reservation Completed')
                    ->body("You completed the reservation for <strong>{$equipmentName}</strong> for " . ($owner?->name ?? 'Unknown user') . ".")
                    ->sendToDatabase($admin);
            })
            ->visible(fn ($record) =>
                $record?->status === 'Approved' &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}
