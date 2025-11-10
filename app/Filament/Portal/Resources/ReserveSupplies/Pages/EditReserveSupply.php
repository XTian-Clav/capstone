<?php

namespace App\Filament\Portal\Resources\ReserveSupplies\Pages;

use App\Models\User;
use App\Models\Supply;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Portal\Resources\ReserveSupplies\ReserveSupplyResource;
use App\Filament\Portal\Resources\ReserveSupplies\Pages\EditReserveSupply;
use App\Filament\Portal\Resources\ReserveSupplies\Pages\ViewReserveSupply;

class EditReserveSupply extends EditRecord
{
    protected static string $resource = ReserveSupplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->record;

        $admin = auth()->user();
        $owner = User::find($record->user_id ?? $data['user_id'] ?? null);

        if (! $owner) {
            return parent::mutateFormDataBeforeSave($data);
        }

        $supply = $record->supply ?? Supply::find($data['supply_id'] ?? null);
        $supplyName = $supply->item_name ?? ($data['item_name'] ?? 'a supply');

        $status = strtolower($data['status'] ?? 'updated');

        Notification::make()
            ->title('Reservation Update')
            ->body('Your reservation for ' . $supplyName . ' has been ' . $status . '.')
            ->actions([
                Action::make('view')
                    ->button()
                    ->color('secondary')
                    ->url(ViewReserveSupply::getUrl([
                        'record' => $record->getRouteKey(),
                    ]), shouldOpenInNewTab: true),
            ])
            ->sendToDatabase($owner);

        Notification::make()
            ->title('Reservation Update Sent')
            ->body('You have ' . $status . ' the reservation for ' . $supplyName . ' for ' . $owner->name . '.')
            ->actions([
                Action::make('view')
                    ->button()
                    ->color('gray')
                    ->url(ViewReserveSupply::getUrl([
                        'record' => $record->getRouteKey(),
                    ]), shouldOpenInNewTab: true),
                Action::make('edit')
                    ->button()
                    ->color('secondary')
                    ->url(EditReserveSupply::getUrl([
                        'record' => $record->getRouteKey(),
                    ]), shouldOpenInNewTab: true),
            ])
            ->sendToDatabase($admin);

        return parent::mutateFormDataBeforeSave($data);
    }
}
