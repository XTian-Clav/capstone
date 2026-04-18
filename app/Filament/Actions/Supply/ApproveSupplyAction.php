<?php

namespace App\Filament\Actions\Supply;

use Filament\Actions\Action;
use App\Models\ReserveSupply;
use Filament\Support\Enums\Size;
use App\Notifications\SupplyApproved;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\ReserveSupplies\Pages\ViewReserveSupply;

class ApproveSupplyAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'approve')
            ->button()
            ->label('Approve')
            ->color('success')
            ->icon('heroicon-o-check')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Approve ' . ($action->getRecord()?->supply?->item_name ?? 'Reservation'))
            ->modalDescription('Are you sure you want to approve this reservation?')
            ->modalSubmitActionLabel('Approve')
            ->action(function (ReserveSupply $record) {
                $supply = $record->supply;
                if (! $supply) {
                    Notification::make()
                        ->title('Cannot Approve')
                        ->body("This reservation has no associated supply.")
                        ->danger()
                        ->send();
                    return;
                }

                if ($supply->quantity < $record->quantity) {
                    Notification::make()
                        ->title('Cannot Approve')
                        ->body("Not enough stock for {$supply->item_name}.")
                        ->danger()
                        ->send();
                    return;
                }

                $record->status = 'Approved';
                $record->save();

                $owner = $record->user;
                $admin = auth()->user();

                $name = $record->supply?->item_name ?? 'an supply';
                $qty = $record->quantity ?? 1;
                $supplyName = "{$name} - Qty: {$qty}";

                if ($owner) {
                    $owner->notify(new SupplyApproved($record));
                }

                Notification::make()
                    ->success()
                    ->color('success')
                    ->title('Supply Reservation Approved')
                    ->body("You approved the reservation for <strong>{$supplyName}</strong> for " . ($owner?->name ?? 'Unknown user') . ".")
                    ->sendToDatabase($admin);
            })
            ->visible(fn ($record) =>
                $record?->status === 'Pending' &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}
