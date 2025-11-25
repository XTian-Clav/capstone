<?php

namespace App\Filament\Actions\Equipment;

use Filament\Actions\Action;
use App\Models\ReserveEquipment;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\ReserveEquipment\Pages\ViewReserveEquipment;

class RejectEquipmentAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'reject')
            ->label('Reject')
            ->color('danger')
            ->icon('heroicon-o-x-mark')
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
                $record->status = 'Rejected';
                $record->admin_comment = $data['admin_comment'] ?? null;
                $record->save();

                $owner = $record->user;
                $admin = auth()->user();
                $equipmentName = $record->equipment?->equipment_name ?? 'an equipment';

                if ($owner) {
                    Notification::make()
                        ->title('Reservation Rejected')
                        ->body("Your reservation for {$equipmentName} has been rejected.")
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
                    ->title('Reservation Rejected')
                    ->body("You rejected the reservation for {$equipmentName} for " . ($owner?->name ?? 'Unknown user') . ".")
                    ->sendToDatabase($admin);
            })
            ->visible(fn ($record) =>
                in_array($record?->status, ['Pending', 'Approved']) &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}
