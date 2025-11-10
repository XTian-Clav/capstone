<?php

namespace App\Filament\Portal\Resources\ReserveEquipment\Pages;

use App\Models\User;
use App\Models\Equipment;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Portal\Resources\ReserveEquipment\ReserveEquipmentResource;
use App\Filament\Portal\Resources\ReserveEquipment\Pages\EditReserveEquipment;
use App\Filament\Portal\Resources\ReserveEquipment\Pages\ViewReserveEquipment;

class EditReserveEquipment extends EditRecord
{
    protected static string $resource = ReserveEquipmentResource::class;

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

        $equipment = $record->equipment ?? Equipment::find($data['equipment_id'] ?? null);
        $equipmentName = $equipment->equipment_name ?? ($data['equipment_name'] ?? 'a equipment');

        $status = strtolower($data['status'] ?? 'updated');

        Notification::make()
            ->title('Reservation Update')
            ->body('Your reservation for ' . $equipmentName . ' has been ' . $status . '.')
            ->actions([
                Action::make('view')
                    ->button()
                    ->color('secondary')
                    ->url(ViewReserveEquipment::getUrl([
                        'record' => $record->getRouteKey(),
                    ]), shouldOpenInNewTab: true),
            ])
            ->sendToDatabase($owner);

        Notification::make()
            ->title('Reservation Update Sent')
            ->body('You have ' . $status . ' the reservation for ' . $equipmentName . ' for ' . $owner->name . '.')
            ->actions([
                Action::make('view')
                    ->button()
                    ->color('gray')
                    ->url(ViewReserveEquipment::getUrl([
                        'record' => $record->getRouteKey(),
                    ]), shouldOpenInNewTab: true),
                Action::make('edit')
                    ->button()
                    ->color('secondary')
                    ->url(EditReserveEquipment::getUrl([
                        'record' => $record->getRouteKey(),
                    ]), shouldOpenInNewTab: true),
            ])
            ->sendToDatabase($admin);

        return parent::mutateFormDataBeforeSave($data);
    }
}
