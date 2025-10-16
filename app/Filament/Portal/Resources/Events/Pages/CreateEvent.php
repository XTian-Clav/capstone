<?php

namespace App\Filament\Portal\Resources\Events\Pages;

use App\Filament\Portal\Resources\Events\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;
}
