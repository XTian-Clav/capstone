<?php

namespace App\Filament\Portal\Resources\Startups\Pages;

use App\Filament\Portal\Resources\Startups\StartupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStartup extends CreateRecord
{
    protected static string $resource = StartupResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
