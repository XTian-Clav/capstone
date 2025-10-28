<?php

namespace App\Filament\Portal\Resources\Startups\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\Startup;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Portal\Resources\Startups\StartupResource;

class EditStartup extends EditRecord
{
    protected static string $resource = StartupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            Actions\RestoreAction::make()->color('success'),
            Actions\ForceDeleteAction::make(),
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

        $status = strtolower($data['status'] ?? 'updated');

        Notification::make()
            ->title('Startup Proposal Update')
            ->body('The reservation for ' . $data['startup_name'] . ' has been ' . $status . '.')
            ->sendToDatabase($owner);

        Notification::make()
            ->title('Startup Proposal Update Sent')
            ->body('You have ' . $status . ' the reservation for ' . $roomName . '.')
            ->sendToDatabase($admin);

        return parent::mutateFormDataBeforeSave($data);
    }
}
