<?php

namespace App\Filament\Portal\Resources\NonUsers\Pages;

use App\Filament\Portal\Resources\NonUsers\NonUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNonUsers extends ListRecords
{
    protected static string $resource = NonUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
