<?php

namespace App\Filament\Portal\Resources\Startups\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\Startup;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Portal\Resources\Startups\StartupResource;
use App\Filament\Portal\Resources\Startups\Pages\EditStartup;
use App\Filament\Portal\Resources\Startups\Pages\ViewStartup;

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
            ->body('Your startup proposal titled ' . $data['startup_name'] . ' has been ' . $status . '.')
            ->actions([
                Action::make('view')
                    ->button()
                    ->color('secondary')
                    ->url(ViewStartup::getUrl([
                        'record' => $record->getRouteKey(),
                    ]), shouldOpenInNewTab: true),
            ])
            ->sendToDatabase($owner);

        Notification::make()
            ->title('Startup Proposal Update Sent')
            ->body('You have ' . $status . ' the startup proposal for ' . $data['startup_name'] . '.')
            ->actions([
                Action::make('view')
                    ->button()
                    ->color('gray')
                    ->url(ViewStartup::getUrl([
                        'record' => $record->getRouteKey(),
                    ]), shouldOpenInNewTab: true),
                Action::make('edit')
                    ->button()
                    ->color('secondary')
                    ->url(EditStartup::getUrl([
                        'record' => $record->getRouteKey(),
                    ]), shouldOpenInNewTab: true),
            ])
            ->sendToDatabase($admin);

        return parent::mutateFormDataBeforeSave($data);
    }
}
