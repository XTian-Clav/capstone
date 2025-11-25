<?php

namespace App\Filament\Actions\Equipment;

use Filament\Actions\Action;
use App\Models\ReserveEquipment;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\ReserveEquipment\Pages\ViewReserveEquipment;

class ApproveEquipmentAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'approve')
            ->label('Approve')
            ->color('success')
            ->icon('heroicon-o-check')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Approve ' . ($action->getRecord()?->equipment?->equipment_name ?? 'Reservation'))
            ->modalDescription('Are you sure you want to approve this reservation?')
            ->modalSubmitActionLabel('Approve')
            ->action(function (ReserveEquipment $record) {
                $record->status = 'Approved';
                $record->save();

                $owner = $record->user;
                $admin = auth()->user();
                $equipmentName = $record->equipment?->equipment_name ?? 'an equipment';

                if ($owner) {
                    Notification::make()
                        ->title('Reservation Approved')
                        ->body("Your reservation for {$equipmentName} has been approved.")
                        ->actions([
                            Action::make('view')
                                ->button()
                                ->color('secondary')
                                ->url(ViewReserveEquipment::getUrl([
                                    'record' => $record->getRouteKey(),
                                ]), shouldOpenInNewTab: true),
                        ])
                        ->sendToDatabase($owner);
                }

                Notification::make()
                    ->title('Reservation Approved')
                    ->body("You approved the reservation for {$equipmentName} for " . ($owner?->name ?? 'Unknown user') . ".")
                    ->sendToDatabase($admin);
            })
            ->visible(fn ($record) =>
                $record?->status === 'Pending' &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}
