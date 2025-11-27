<?php

namespace App\Filament\Actions\Supply;

use Filament\Actions\Action;
use App\Models\ReserveSupply;
use Filament\Support\Enums\Size;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\ReserveSupplies\Pages\ViewReserveSupply;

class CompleteSupplyAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'complete')
            ->button()
            ->outlined()
            ->size(Size::ExtraSmall)
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
                $supplyName = $record->supply?->item_name ?? 'an supply';

                if ($owner) {
                    Notification::make()
                        ->title('Reservation Completed')
                        ->body("Your reservation for {$supplyName} has been completed.")
                        ->actions([
                            Action::make('view')
                                ->button()
                                ->color('secondary')
                                ->url(ViewReserveSupply::getUrl([
                                    'record' => $record->getRouteKey(),
                                ]), shouldOpenInNewTab: true),
                        ])
                        ->sendToDatabase($owner);
                }

                Notification::make()
                    ->title('Reservation Completed')
                    ->body("You completed the reservation for {$supplyName} for " . ($owner?->name ?? 'Unknown user') . ".")
                    ->sendToDatabase($admin);
            })
            ->visible(fn ($record) =>
                $record?->status === 'Approved' &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}
