<?php

namespace App\Filament\Portal\Resources\Startups\Pages;

use App\Filament\Portal\Resources\Startups\StartupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStartup extends CreateRecord
{
    protected static string $resource = StartupResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        if (! $user->hasAnyRole(['admin', 'super_admin'])) {
            $data['status'] = 'Pending';
        }

        return $data;
    }
}
