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
        $supplyName = Supply::find($data['supply_id'] ?? null)?->item_name ?? 'a supply';

        if (! $user->hasAnyRole(['admin', 'super_admin'])) {
            Notification::make()
                ->color('warning')
                ->iconColor('warning')
                ->icon('heroicon-o-clock')
                ->title('Reservation Submitted')
                ->body('Your reservation for ' . $supplyName . ' has been submitted successfully.')
                ->sendToDatabase($user);
        }

        return $data;
    }
}
