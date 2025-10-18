<?php

namespace App\Filament\Portal\Resources\Guides\Pages;

use App\Filament\Portal\Resources\Guides\GuideResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGuides extends ListRecords
{
    protected static string $resource = GuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
