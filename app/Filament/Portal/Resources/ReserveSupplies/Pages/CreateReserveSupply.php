<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Pages;

use App\Models\Supply;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Portal\Resources\ReserveSupplies\ReserveSupplyResource;

class CreateReserveSupply extends CreateRecord
{
    protected static string $resource = ReserveSupplyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        $user = auth()->user();

        $supply = Supply::find($data['supply_id'] ?? null);
        $name = $supply?->item_name ?? 'an supply';
        $qty = $data['quantity'] ?? 1;

        $supplyName = "<strong>{$name} - Qty: {$qty}</strong>";

        if (! $user->hasAnyRole(['admin', 'super_admin'])) {
            Notification::make()
                ->color('warning')
                ->iconColor('warning')
                ->icon('heroicon-m-clock')
                ->title('Supply Reservation Submitted')
                ->body('Your reservation for ' . $supplyName . ' has been submitted successfully.')
                ->sendToDatabase($user);
        }

        return $data;
    }
}
