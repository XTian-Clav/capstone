<?php

namespace App\Filament\Actions\Supply;

use Filament\Actions\Action;
use App\Models\ReserveSupply;
use Filament\Support\Enums\Size;
use App\Notifications\SupplyCompleted;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\ReserveSupplies\Pages\ViewReserveSupply;

class CompleteSupplyAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'complete')
            ->button()
            ->label('Complete')
            ->color('cyan')
            ->icon('heroicon-m-check-badge')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Complete ' . ($action->getRecord()?->supply?->item_name ?? 'Reservation'))
            ->modalDescription('Mark this reservation as completed?')
            ->modalSubmitActionLabel('Complete')
            ->action(function (ReserveSupply $record) {
                $supply = $record->supply;
                if (! $supply) {
                    Notification::make()
                        ->title('Cannot Complete')
                        ->body("This reservation has no associated supply.")
                        ->danger()
                        ->send();
                    return;
                }

                $record->status = 'Completed';
                $record->save();

                $owner = $record->user;
                $admin = auth()->user();
                
                $name = $record->supply?->item_name ?? 'an supply';
                $qty = $record->quantity ?? 1;
                $supplyName = "{$name} - Qty: {$qty}";

                if ($owner) {
                    $owner->notify(new SupplyCompleted($record));
                }

                Notification::make()
                    ->color('cyan')
                    ->iconColor('cyan')
                    ->icon('heroicon-m-check-badge')
                    ->title('Supply Reservation Completed')
                    ->body("You completed the reservation for <strong>{$supplyName}</strong> for " . ($owner?->name ?? 'Unknown user') . ".")
                    ->sendToDatabase($admin);
            })
            ->visible(fn ($record) =>
                $record?->status === 'Approved' &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}
