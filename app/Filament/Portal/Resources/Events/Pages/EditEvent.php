<?php

namespace App\Filament\Portal\Resources\Events\Pages;

use App\Filament\Portal\Resources\Events\EventResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return $this->getRecord()->event;
    }
}
