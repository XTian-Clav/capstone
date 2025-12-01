<?php

namespace App\Filament\Actions\Supply;

use Filament\Actions\Action;
use App\Models\ReserveSupply;
use Filament\Support\Enums\Size;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use App\Filament\Portal\Resources\ReserveSupplies\Pages\ViewReserveSupply;

class RejectsupplyAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'reject')
            ->button()
            ->label('Reject')
            ->color('danger')
            ->icon('heroicon-o-x-mark')
            ->requiresConfirmation()
            ->modalHeading(fn ($action) => 'Reject ' . ($action->getRecord()?->supply?->item_name ?? 'Reservation'))
            ->modalDescription('Are you sure you want to reject this reservation?')
            ->modalSubmitActionLabel('Reject')
            ->schema([
                Textarea::make('admin_comment')
                    ->label('Reason for rejection')
                    ->required()
                    ->rows(6)
                    ->placeholder('Enter reason for rejection...'),
            ])
            ->action(function (Reservesupply $record, array $data) {
                $supply = $record->supply;
                if (! $supply) {
                    Notification::make()
                        ->title('Cannot Reject')
                        ->body("This reservation has no associated supply.")
                        ->danger()
                        ->send();
                    return;
                }

                $record->status = 'Rejected';
                $record->admin_comment = $data['admin_comment'] ?? null;
                $record->save();

                $owner = $record->user;
                $admin = auth()->user();
                $supplyName = $record->supply?->item_name ?? 'an supply';

                if ($owner) {
                    Notification::make()
                        ->danger()
                        ->color('danger')
                        ->title('Reservation Rejected')
                        ->body("Your reservation for {$supplyName} has been rejected.")
                        ->actions([
                            Action::make('view')
                                ->button()
                                ->outlined()
                                ->color('gray')
                                ->url(ViewReservesupply::getUrl([
                                    'record' => $record->getRouteKey(),
                                ]), shouldOpenInNewTab: true),
                        ])
                        ->sendToDatabase($owner);
                }

                Notification::make()
                    ->danger()
                    ->color('danger')
                    ->title('Reservation Rejected')
                    ->body("You rejected the reservation for {$supplyName} for " . ($owner?->name ?? 'Unknown user') . ".")
                    ->sendToDatabase($admin);
            })
            ->visible(fn ($record) =>
                in_array($record?->status, ['Pending', 'Approved']) &&
                auth()->user()->hasAnyRole(['super_admin', 'admin'])
            );
    }
}
