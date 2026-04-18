<?php

namespace App\Filament\Actions\Equipment;

use Filament\Actions\Action;
use App\Models\ReserveEquipment;
use Filament\Support\Enums\Size;
use Filament\Forms\Components\Textarea;
use App\Notifications\EquipmentRejected;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\ReserveEquipment\Pages\ViewReserveEquipment;

class RejectEquipmentAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'reject')
            ->button()
            ->label('Reject')
            ->color('danger')
            ->icon('heroicon-m-x-circle')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Reject ' . ($action->getRecord()?->equipment?->equipment_name ?? 'Reservation'))
            ->modalDescription('Are you sure you want to reject this reservation?')
            ->modalSubmitActionLabel('Reject')
            ->schema([
                Textarea::make('admin_comment')
                    ->label('Reason for rejection')
                    ->required()
                    ->rows(6)
                    ->placeholder('Enter reason for rejection...'),
            ])
            ->action(function (ReserveEquipment $record, array $data) {
                $equipment = $record->equipment;
                if (! $equipment) {
                    Notification::make()
                        ->title('Cannot Reject')
                        ->body("This reservation has no associated equipment.")
                        ->danger()
                        ->send();
                    return;
                }

                $record->status = 'Rejected';
                $record->admin_comment = $data['admin_comment'] ?? null;
                $record->save();

                $owner = $record->user;
                $admin = auth()->user();
                
                $name = $record->equipment?->equipment_name ?? 'an equipment';
                $qty = $record->quantity ?? 1;
                $equipmentName = "{$name} - Qty: {$qty}";

                if ($owner) {
                    $owner->notify(new EquipmentRejected($record));
                }

                Notification::make()
                    ->color('danger')
                    ->iconColor('danger')
                    ->icon('heroicon-m-x-circle')
                    ->title('Equipment Reservation Rejected')
                    ->body("You rejected the reservation for <strong>{$equipmentName}</strong> for " . ($owner?->name ?? 'Unknown user') . ".")
                    ->sendToDatabase($admin);
            })
            ->visible(fn ($record) =>
                in_array($record?->status, ['Pending', 'Approved']) &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}
