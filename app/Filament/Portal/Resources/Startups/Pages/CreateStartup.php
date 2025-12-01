<?php

namespace App\Filament\Portal\Resources\Startups\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Portal\Resources\Startups\StartupResource;

class CreateStartup extends CreateRecord
{
    protected static string $resource = StartupResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $user = auth()->user();

        // Only notify non-admin users
        if (! $user->hasAnyRole(['admin', 'super_admin'])) {
            Notification::make()
                ->color('warning')
                ->iconColor('warning')
                ->icon('heroicon-o-clock')
                ->title('Startup Proposal Submitted')
                ->body('Your startup proposal "' . $data['startup_name'] . '" has been submitted successfully. Please wait at least 2-3 business days for approval')
                ->sendToDatabase($user);
        }

        return $data;
    }
}
